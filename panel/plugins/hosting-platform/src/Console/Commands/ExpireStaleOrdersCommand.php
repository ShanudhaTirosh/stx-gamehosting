<?php

namespace BrandedHosting\Platform\Console\Commands;

use BrandedHosting\Platform\Enums\OrderStatus;
use BrandedHosting\Platform\Models\HostingOrder;
use Illuminate\Console\Command;

class ExpireStaleOrdersCommand extends Command
{
    protected $signature = 'hosting:expire-orders';

    protected $description = 'Mark unpaid orders whose payment window has passed as expired';

    public function handle(): int
    {
        // Only orders with no payment submitted. Orders under review wait for staff, however long that takes.
        $count = HostingOrder::query()
            ->where('status', OrderStatus::PendingPayment->value)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['status' => OrderStatus::Expired->value]);

        $this->info("Expired {$count} unpaid order(s).");

        return self::SUCCESS;
    }
}
