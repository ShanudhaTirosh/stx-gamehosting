<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\StatusIncidents;

use BrandedHosting\Platform\Filament\Admin\Resources\StatusIncidents\Pages\ManageStatusIncidents;
use BrandedHosting\Platform\Models\StatusIncident;
use BrandedHosting\Platform\Models\StatusIncidentUpdate;
use BrandedHosting\Platform\Models\StatusMonitor;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StatusIncidentResource extends Resource
{
    protected static ?string $model = StatusIncident::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-alert-triangle';

    protected static string|\UnitEnum|null $navigationGroup = 'Status & Telemetry';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Incident & Maintenance Information')
                    ->components([
                        TextInput::make('title')
                            ->label('Title / Summary')
                            ->placeholder('e.g. Upgrading Colombo DC Core Switches')
                            ->required()
                            ->columnSpanFull(),

                        Select::make('type')
                            ->label('Event Type')
                            ->options([
                                'incident' => 'Incident (Unplanned Outage / Degradation)',
                                'maintenance' => 'Maintenance (Scheduled Work / Migration)',
                            ])
                            ->default('incident')
                            ->required(),

                        Select::make('status')
                            ->label('Current Progress Status')
                            ->options([
                                'investigating' => 'Investigating',
                                'identified' => 'Identified',
                                'monitoring' => 'Monitoring & Verifying',
                                'resolved' => 'Resolved',
                                'scheduled' => 'Scheduled (Maintenance)',
                                'in_progress' => 'In Progress (Maintenance)',
                                'completed' => 'Completed (Maintenance)',
                            ])
                            ->default('investigating')
                            ->required(),

                        Select::make('impact')
                            ->label('Customer Impact Level')
                            ->options([
                                'none' => 'None / Informational Notice',
                                'minor' => 'Minor Performance Impact',
                                'major' => 'Major Service Degradation',
                                'critical' => 'Critical Full Outage',
                            ])
                            ->default('minor')
                            ->required(),

                        Select::make('affected_monitors')
                            ->label('Affected Service Monitors')
                            ->multiple()
                            ->options(fn () => StatusMonitor::pluck('name', 'id')->toArray())
                            ->columnSpanFull(),

                        DateTimePicker::make('scheduled_start')
                            ->label('Scheduled Start'),

                        DateTimePicker::make('scheduled_end')
                            ->label('Scheduled End'),

                        DateTimePicker::make('resolved_at')
                            ->label('Resolved At'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Incident / Maintenance')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'danger' => 'incident',
                        'warning' => 'maintenance',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'danger' => 'investigating',
                        'warning' => fn ($state) => in_array($state, ['identified', 'in_progress']),
                        'info' => fn ($state) => in_array($state, ['monitoring', 'scheduled']),
                        'success' => fn ($state) => in_array($state, ['resolved', 'completed']),
                    ])
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state))),

                TextColumn::make('impact')
                    ->badge()
                    ->colors([
                        'gray' => 'none',
                        'info' => 'minor',
                        'warning' => 'major',
                        'danger' => 'critical',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state) . ' Impact'),

                TextColumn::make('created_at')
                    ->label('Reported')
                    ->since()
                    ->sortable(),

                TextColumn::make('resolved_at')
                    ->label('Resolved')
                    ->dateTime('M d, H:i')
                    ->placeholder('Active / Open'),
            ])
            ->actions([
                Action::make('add_update')
                    ->label('Post Update')
                    ->icon('tabler-message-plus')
                    ->color('info')
                    ->form([
                        Select::make('status')
                            ->label('Status at this update')
                            ->options([
                                'investigating' => 'Investigating',
                                'identified' => 'Identified',
                                'monitoring' => 'Monitoring',
                                'resolved' => 'Resolved',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                            ])
                            ->required(),
                        Textarea::make('message')
                            ->label('Public Telemetry Update Message')
                            ->placeholder('e.g. Technicians have isolated the faulty fiber optic port and traffic is re-routing.')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (StatusIncident $record, array $data) {
                        StatusIncidentUpdate::create([
                            'status_incident_id' => $record->id,
                            'status' => $data['status'],
                            'message' => $data['message'],
                        ]);

                        $record->update(['status' => $data['status']]);
                        if (in_array($data['status'], ['resolved', 'completed']) && !$record->resolved_at) {
                            $record->update(['resolved_at' => now()]);
                        }

                        Notification::make()
                            ->title('Incident Timeline Updated')
                            ->body('Update note successfully broadcasted to public status page.')
                            ->success()
                            ->send();
                    }),

                Action::make('mark_resolved')
                    ->label('Resolve')
                    ->icon('tabler-check')
                    ->color('success')
                    ->visible(fn (StatusIncident $record) => !in_array($record->status, ['resolved', 'completed']))
                    ->requiresConfirmation()
                    ->action(function (StatusIncident $record) {
                        $targetStatus = $record->type === 'maintenance' ? 'completed' : 'resolved';
                        $record->update([
                            'status' => $targetStatus,
                            'resolved_at' => now(),
                        ]);

                        StatusIncidentUpdate::create([
                            'status_incident_id' => $record->id,
                            'status' => $targetStatus,
                            'message' => 'All systems operational. Telemetry verified normal.',
                        ]);

                        Notification::make()
                            ->title('Incident Resolved')
                            ->success()
                            ->send();
                    }),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageStatusIncidents::route('/'),
        ];
    }
}
