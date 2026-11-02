<?php

use BrandedHosting\Platform\Enums\BillingPeriod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hosting_orders', function (Blueprint $table) {
            // 'new' = the original order that created a server, 'renewal' = a later invoice for it.
            $table->string('order_type')->default('new')->after('order_number');
            $table->foreignId('renews_order_id')->nullable()->after('order_type')->constrained('hosting_orders')->nullOnDelete();
            // Only set on 'new' orders: the date until which the server is paid for.
            $table->dateTime('paid_until')->nullable()->after('provisioned_at');
            $table->dateTime('suspended_at')->nullable()->after('paid_until');
            // Highest billing email stage sent for the current cycle (1 invoice, 2 first reminder, 3 final reminder, 4 suspended).
            $table->unsignedTinyInteger('billing_stage')->default(0)->after('suspended_at');

            $table->index(['order_type', 'status', 'paid_until']);
        });

        // Give servers that already exist a sensible paid-until date. Never in the past: customers
        // who are live today get at least a week before the first renewal invoice, so deploying
        // this cannot suddenly suspend anyone.
        $floor = now()->addDays(7);
        DB::table('hosting_orders')
            ->where('status', 'completed')
            ->whereNotNull('server_id')
            ->orderBy('id')
            ->each(function ($order) use ($floor) {
                $snapshot = json_decode($order->plan_snapshot ?? '[]', true) ?: [];
                $days = BillingPeriod::tryFrom($snapshot['billing_period'] ?? '')?->getDurationInDays();
                if ($days === null) {
                    return; // one-time plans never expire
                }
                $start = $order->provisioned_at ?? $order->created_at;
                $until = \Illuminate\Support\Carbon::parse($start)->addDays($days);
                DB::table('hosting_orders')->where('id', $order->id)->update([
                    'paid_until' => $until->lt($floor) ? $floor : $until,
                ]);
            });
    }

    public function down(): void
    {
        Schema::table('hosting_orders', function (Blueprint $table) {
            $table->dropIndex(['order_type', 'status', 'paid_until']);
            $table->dropConstrainedForeignId('renews_order_id');
            $table->dropColumn(['order_type', 'paid_until', 'suspended_at', 'billing_stage']);
        });
    }
};
