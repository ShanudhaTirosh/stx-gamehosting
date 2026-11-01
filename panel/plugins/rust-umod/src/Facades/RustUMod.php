<?php

namespace Boy132\RustUMod\Facades;

use App\Models\Server;
use Boy132\RustUMod\Services\RustUModService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static ?string getRustModdingFramework(Server $server)
 * @method static bool isRustServer(Server $server)
 * @method static array{data: array<int, array<string, mixed>>, total: int} getUModPlugins(int $page = 1, string $search = '')
 * @method static array<string, mixed> getUModPlugin(string $pluginName)
 *
 * @see RustUModService
 */
class RustUMod extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RustUModService::class;
    }
}
