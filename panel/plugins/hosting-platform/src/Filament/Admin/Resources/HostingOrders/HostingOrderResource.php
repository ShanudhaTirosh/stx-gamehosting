<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\HostingOrders;

use App\Services\Servers\ServerCreationService;
use BrandedHosting\Platform\Enums\OrderStatus;
use BrandedHosting\Platform\Enums\VerificationStatus;
use BrandedHosting\Platform\Filament\Admin\Resources\HostingOrders\Pages\ListHostingOrders;
use BrandedHosting\Platform\Jobs\ProvisionPaidServerJob;
use BrandedHosting\Platform\Models\HostingOrder;
use BrandedHosting\Platform\Models\HostingPaymentSubmission;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Throwable;

class HostingOrderResource extends Resource
{
    protected static ?string $model = HostingOrder::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-shopping-cart';

    protected static string|\UnitEnum|null $navigationGroup = 'Hosting Platform';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $pendingCount = HostingOrder::whereIn('status', [OrderStatus::UnderReview, OrderStatus::PendingPayment])->count();
        return $pendingCount > 0 ? (string) $pendingCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order #')
                    ->fontFamily('mono')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.username')
                    ->label('Customer')
                    ->description(fn (HostingOrder $order) => $order->user?->email)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('plan.name')
                    ->label('Plan')
                    ->description(fn (HostingOrder $order) => $order->plan?->product?->name)
                    ->sortable(),
                TextColumn::make('total_amount_lkr')
                    ->label('Total (LKR)')
                    ->formatStateUsing(fn ($state) => 'Rs. ' . number_format((float) $state, 2))
                    ->sortable(),
                TextColumn::make('paymentMethod.name')
                    ->label('Payment Rail')
                    ->badge()
                    ->placeholder('Unselected'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('server.name')
                    ->label('Server Instance')
                    ->placeholder('Not Provisioned')
                    ->icon('tabler-brand-docker')
                    ->color(fn (HostingOrder $order) => $order->server_id ? 'success' : 'gray')
                    ->url(fn (HostingOrder $order) => $order->server ? "/admin/servers/{$order->server->id}" : null),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(OrderStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])),
            ])
            ->actions([
                // 1. Direct Instant "Approve & Deploy" Button
                Action::make('approve_and_deploy')
                    ->label('Approve & Deploy')
                    ->icon('tabler-player-play')
                    ->color('success')
                    ->visible(fn (HostingOrder $order) => in_array($order->status, [OrderStatus::PendingPayment, OrderStatus::UnderReview, OrderStatus::Approved]) && $order->server_id === null)
                    ->requiresConfirmation()
                    ->modalHeading(fn (HostingOrder $order) => "Approve & Deploy Server for Order #{$order->order_number}?")
                    ->modalDescription('This will verify the payment and immediately allocate a container, assign ports, and activate the customer game server.')
                    ->action(function (HostingOrder $order) {
                        $order->update([
                            'status' => OrderStatus::Approved,
                            'approved_at' => now(),
                        ]);

                        if ($submission = $order->latestSubmission) {
                            $submission->update([
                                'verification_status' => VerificationStatus::Approved,
                                'verified_by_user_id' => auth()->id(),
                                'verified_at' => now(),
                                'staff_decision_notes' => 'Instant approval & automated deployment initiated by administrator.',
                            ]);
                        }

                        try {
                            $job = new ProvisionPaidServerJob($order->id);
                            $job->handle(app(ServerCreationService::class));

                            Notification::make()
                                ->title('Server Deployed & Active!')
                                ->body("Order #{$order->order_number} has been verified and provisioned successfully.")
                                ->success()
                                ->send();
                        } catch (Throwable $e) {
                            dispatch(new ProvisionPaidServerJob($order->id));

                            Notification::make()
                                ->title('Provisioning Dispatched')
                                ->body("Deployment queued for Order #{$order->order_number}: " . $e->getMessage())
                                ->warning()
                                ->send();
                        }
                    }),

                // 2. Direct Link to Server Console
                Action::make('view_server')
                    ->label('Server')
                    ->icon('tabler-external-link')
                    ->color('success')
                    ->visible(fn (HostingOrder $order) => $order->server_id !== null)
                    ->url(fn (HostingOrder $order) => "/admin/servers/{$order->server_id}")
                    ->openUrlInNewTab(),

                // 3. View Invoice PDF
                Action::make('view_invoice')
                    ->label('Invoice')
                    ->icon('tabler-receipt')
                    ->color('gray')
                    ->url(fn (HostingOrder $order) => route('hosting.order.invoice', ['uuid' => $order->uuid]))
                    ->openUrlInNewTab(),

                // 4. Verification & Proof Modal Action
                Action::make('verify_payment')
                    ->label('Verify Proof')
                    ->icon('tabler-file-search')
                    ->color('warning')
                    ->visible(fn (HostingOrder $order) => in_array($order->status, [OrderStatus::UnderReview, OrderStatus::PendingPayment]))
                    ->modalHeading(fn (HostingOrder $order) => "Verify Payment for Order #{$order->order_number}")
                    ->modalContent(function (HostingOrder $order) {
                        $submission = $order->latestSubmission;
                        $proof = $submission?->proofs?->first();

                        $proofHtml = '<div class="p-4 text-center text-gray-500">No payment proof uploaded yet.</div>';
                        if ($proof) {
                            if ($proof->isImage()) {
                                $proofHtml = '
                                    <div class="mt-4 border border-gray-700 rounded-xl overflow-hidden bg-gray-950 p-2">
                                        <img src="' . $proof->getDownloadUrl() . '" alt="Payment Proof" class="max-h-96 mx-auto rounded-lg object-contain">
                                    </div>
                                    <div class="mt-2 text-center">
                                        <a href="' . $proof->getDownloadUrl() . '" target="_blank" class="text-xs text-indigo-400 hover:underline">Open Full Size in New Tab</a>
                                    </div>
                                ';
                            } else {
                                $proofHtml = '
                                    <div class="mt-4 p-6 border border-gray-700 rounded-xl bg-gray-950 text-center">
                                        <i class="ti ti-file-text text-4xl text-indigo-400"></i>
                                        <div class="text-sm font-bold text-white mt-2">' . htmlspecialchars($proof->original_filename) . '</div>
                                        <a href="' . $proof->getDownloadUrl() . '" target="_blank" class="mt-3 inline-block px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs font-bold">Download PDF Slip</a>
                                    </div>
                                ';
                            }
                        }

                        $ref = htmlspecialchars($submission?->transaction_reference ?? 'None');
                        $notes = htmlspecialchars($submission?->customer_notes ?? 'None');
                        $method = htmlspecialchars($order->paymentMethod?->name ?? 'None');
                        $total = $order->formatted_total;

                        return new HtmlString('
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4 p-4 rounded-xl bg-gray-900 border border-gray-800 text-xs">
                                    <div><span class="text-gray-400">Expected Total:</span> <strong class="text-white text-sm">' . $total . '</strong></div>
                                    <div><span class="text-gray-400">Payment Rail:</span> <strong class="text-white">' . $method . '</strong></div>
                                    <div><span class="text-gray-400">Customer Txn Ref:</span> <span class="font-mono font-bold text-indigo-400 select-all">' . $ref . '</span></div>
                                    <div><span class="text-gray-400">Customer Notes:</span> <span class="text-gray-300">' . $notes . '</span></div>
                                </div>
                                ' . $proofHtml . '
                            </div>
                        ');
                    })
                    ->form([
                        Textarea::make('staff_notes')
                            ->label('Staff Verification Notes / Decision Reason')
                            ->placeholder('e.g. Verified with Commercial Bank CDM Slip #8821.')
                            ->rows(2),
                    ])
                    ->extraModalFooterActions([
                        Action::make('reject')
                            ->label('Reject Payment')
                            ->color('danger')
                            ->icon('tabler-circle-x')
                            ->requiresConfirmation()
                            ->action(function (HostingOrder $order, array $data) {
                                $order->update(['status' => OrderStatus::Rejected]);

                                if ($submission = $order->latestSubmission) {
                                    $submission->update([
                                        'verification_status' => VerificationStatus::Rejected,
                                        'verified_by_user_id' => auth()->id(),
                                        'verified_at' => now(),
                                        'staff_decision_notes' => $data['staff_notes'] ?? 'Payment rejected by staff.',
                                    ]);
                                }

                                Notification::make()
                                    ->title('Payment Rejected')
                                    ->body("Order #{$order->order_number} has been marked as rejected.")
                                    ->warning()
                                    ->send();
                            }),
                    ])
                    ->action(function (HostingOrder $order, array $data) {
                        $order->update([
                            'status' => OrderStatus::Approved,
                            'approved_at' => now(),
                        ]);

                        if ($submission = $order->latestSubmission) {
                            $submission->update([
                                'verification_status' => VerificationStatus::Approved,
                                'verified_by_user_id' => auth()->id(),
                                'verified_at' => now(),
                                'staff_decision_notes' => $data['staff_notes'] ?? 'Verified and approved.',
                            ]);
                        }

                        try {
                            $job = new ProvisionPaidServerJob($order->id);
                            $job->handle(app(ServerCreationService::class));

                            Notification::make()
                                ->title('Payment Approved & Provisioned!')
                                ->body("Order #{$order->order_number} approved and server container is now active.")
                                ->success()
                                ->send();
                        } catch (Throwable $e) {
                            dispatch(new ProvisionPaidServerJob($order->id));

                            Notification::make()
                                ->title('Payment Approved!')
                                ->body("Order #{$order->order_number} approved. Provisioning job dispatched.")
                                ->success()
                                ->send();
                        }
                    }),

                // 5. Retry Deploy Action
                Action::make('retry_provisioning')
                    ->label('Retry Deploy')
                    ->icon('tabler-reload')
                    ->color('danger')
                    ->visible(fn (HostingOrder $order) => $order->status === OrderStatus::Approved || ($order->provisioningRecord?->status === \BrandedHosting\Platform\Enums\ProvisioningStatus::Failed))
                    ->requiresConfirmation()
                    ->action(function (HostingOrder $order) {
                        try {
                            $job = new ProvisionPaidServerJob($order->id);
                            $job->handle(app(ServerCreationService::class));

                            Notification::make()
                                ->title('Server Deployed!')
                                ->body("Order #{$order->order_number} provisioned successfully.")
                                ->success()
                                ->send();
                        } catch (Throwable $e) {
                            dispatch(new ProvisionPaidServerJob($order->id));

                            Notification::make()
                                ->title('Provisioning Retried')
                                ->body("Dispatched provisioning job for Order #{$order->order_number}.")
                                ->info()
                                ->send();
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHostingOrders::route('/'),
        ];
    }
}
