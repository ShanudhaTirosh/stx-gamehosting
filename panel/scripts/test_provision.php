<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use BrandedHosting\Platform\Jobs\ProvisionPaidServerJob;
use App\Services\Servers\ServerCreationService;
use BrandedHosting\Platform\Models\HostingOrder;

$order = HostingOrder::first();
echo "Order before: status={$order->status->value}, server_id=" . ($order->server_id ?? 'null') . "\n";

$job = new ProvisionPaidServerJob($order->id);
$creationService = $app->make(ServerCreationService::class);
$job->handle($creationService);

$order->refresh();
echo "Order after: status={$order->status->value}, server_id=" . ($order->server_id ?? 'null') . "\n";
if ($order->server) {
    echo "Server created: #{$order->server->id} ({$order->server->uuid_short})\n";
    echo "Node: " . $order->server->node->name . "\n";
    echo "Allocation: " . ($order->server->allocation ? $order->server->allocation->display_address : 'None') . "\n";
}
