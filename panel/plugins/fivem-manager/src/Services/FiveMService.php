<?php

namespace BrandedHosting\FiveM\Services;

use App\Models\EggVariable;
use App\Models\Server;
use App\Models\ServerVariable;
use App\Repositories\Daemon\DaemonFileRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class FiveMService
{
    /**
     * Determine if a server is running FiveM.
     */
    public static function isFiveMServer(?Server $server): bool
    {
        if (!$server) {
            return false;
        }

        $server->loadMissing('egg');
        if (!$server->egg) {
            return false;
        }

        $name = strtolower($server->egg->name);
        $features = $server->egg->features ?? [];

        return str_contains($name, 'fivem') || in_array('fivem', $features, true);
    }

    /**
     * List of all GTA V DLC Game Builds.
     */
    public static function getGameBuilds(): array
    {
        return [
            [
                'id' => 'los_santos_tuners',
                'name' => 'LOS SANTOS TUNERS',
                'build' => '2372',
                'description' => 'Press "activate" to activate the DLC Los Santos TUNERS.',
                'dlc_release' => 'Summer 2021',
            ],
            [
                'id' => 'cayo_perico',
                'name' => 'Cayo Perico',
                'build' => '2189',
                'description' => 'Press "activate" to activate the DLC Cayo Perico.',
                'dlc_release' => 'December 2020',
            ],
            [
                'id' => 'casino_last_dlc',
                'name' => 'Casino + Dernier DLC',
                'build' => '2060',
                'description' => 'Press "activate" to activate the DLC Casino + Last DLC.',
                'dlc_release' => 'Summer 2020',
            ],
            [
                'id' => 'contract_dlc',
                'name' => 'Contract DLC',
                'build' => '2545',
                'description' => 'Press "activate" to activate the Contract.',
                'dlc_release' => 'December 2021',
            ],
            [
                'id' => 'criminal_enterprises',
                'name' => 'Criminal Enterprises DLC',
                'build' => '2699',
                'description' => 'Press "activate" to activate the Criminal entreprise.',
                'dlc_release' => 'July 2022',
            ],
            [
                'id' => 'drug_wars',
                'name' => 'Los Santos Drug Wars',
                'build' => '2802',
                'description' => 'Press "activate" to activate the Los Santos Drug Wars DLC.',
                'dlc_release' => 'December 2022',
            ],
            [
                'id' => 'mercenaries',
                'name' => 'San Andreas Mercenaries',
                'build' => '2944',
                'description' => 'Press "activate" to activate the San Andreas Mercenaries DLC.',
                'dlc_release' => 'June 2023',
            ],
            [
                'id' => 'the_chop_shop',
                'name' => 'The Chop Shop',
                'build' => '3095',
                'description' => 'Press "activate" to activate The Chop Shop DLC.',
                'dlc_release' => 'December 2023',
            ],
            [
                'id' => 'bottom_dollar',
                'name' => 'Bottom Dollar Bounties',
                'build' => '3258',
                'description' => 'Press "activate" to activate the Bottom Dollar Bounties DLC.',
                'dlc_release' => 'June 2024',
            ],
            [
                'id' => 'agents_sabotage',
                'name' => 'Agents of Sabotage',
                'build' => '3407',
                'description' => 'Press "activate" to activate the Agents of Sabotage DLC.',
                'dlc_release' => 'December 2024',
            ],
            [
                'id' => 'default_vanilla',
                'name' => 'Vanilla Base GTA V',
                'build' => '1604',
                'description' => 'Default base GTA V engine without modern DLC enforcement.',
                'dlc_release' => 'Legacy 1604',
            ],
        ];
    }

    /**
     * Read the active GameBuild from server.cfg with cache fallback.
     */
    public static function getActiveGameBuild(Server $server): ?string
    {
        $cached = Cache::get("fivem:gamebuild:{$server->id}");

        try {
            $content = static::readConfigFile($server);
            if (!empty($content) && preg_match('/(?:set\s+)?sv_enforceGameBuild\s+["\']?([0-9]+)["\']?/i', $content, $matches)) {
                Cache::forever("fivem:gamebuild:{$server->id}", $matches[1]);
                return $matches[1];
            }
        } catch (Throwable $e) {
            Log::warning("Could not read active GameBuild for server {$server->id}: " . $e->getMessage());
        }

        return $cached;
    }

    /**
     * Update or append set sv_enforceGameBuild <build> in server.cfg.
     */
    public static function setGameBuild(Server $server, string $build): bool
    {
        Cache::forever("fivem:gamebuild:{$server->id}", $build);

        try {
            $content = static::readConfigFile($server);
            $newLine = "set sv_enforceGameBuild {$build}";

            if (empty($content)) {
                // If server.cfg doesn't exist yet, create a baseline
                $content = "# FiveM Server Configuration\n{$newLine}\n";
            } elseif (preg_match('/(?:set\s+)?sv_enforceGameBuild\s+["\']?[0-9]+["\']?/i', $content)) {
                $content = preg_replace('/(?:set\s+)?sv_enforceGameBuild\s+["\']?[0-9]+["\']?/i', $newLine, $content);
            } else {
                $content = trim($content) . "\n\n# Enforced GTA V Game DLC Build\n{$newLine}\n";
            }

            static::writeConfigFile($server, $content);

            return true;
        } catch (Throwable $e) {
            Log::warning("Wings daemon offline or failed to write GameBuild {$build} for server {$server->id}: " . $e->getMessage());

            // Build is cached and will sync upon daemon reconnect
            return true;
        }
    }

    /**
     * Fetch available FXServer artifacts from Cfx.re.
     */
    public static function fetchArtifacts(): array
    {
        return Cache::remember('fivem_artifacts_feed', 300, function () {
            try {
                $response = Http::timeout(6)->get('https://changelogs-live.fivem.net/api/changelog/versions/linux/server');
                if ($response->successful()) {
                    $data = $response->json();

                    return [
                        'recommended' => $data['recommended'] ?? 'Latest Recommended',
                        'recommended_download' => $data['recommended_download'] ?? null,
                        'latest' => $data['latest'] ?? 'Latest Bleeding Edge',
                        'latest_download' => $data['latest_download'] ?? null,
                    ];
                }
            } catch (Throwable $e) {
                Log::warning('Error fetching FiveM artifacts: ' . $e->getMessage());
            }

            return [
                'recommended' => 'recommended',
                'recommended_download' => null,
                'latest' => 'latest',
                'latest_download' => null,
            ];
        });
    }

    /**
     * Get the current FIVEM_VERSION setting of a server.
     */
    public static function getCurrentArtifactVersion(Server $server): string
    {
        $server->loadMissing('variables');
        $variable = $server->variables->firstWhere('env_variable', 'FIVEM_VERSION');

        return $variable?->server_value ?: 'recommended';
    }

    /**
     * Set the FIVEM_VERSION environment variable on the server.
     */
    public static function setArtifactVersion(Server $server, string $version): bool
    {
        $server->loadMissing('variables');
        $eggVar = EggVariable::where('egg_id', $server->egg_id)
            ->where('env_variable', 'FIVEM_VERSION')
            ->first();

        if (!$eggVar) {
            return false;
        }

        ServerVariable::updateOrCreate(
            ['server_id' => $server->id, 'variable_id' => $eggVar->id],
            ['variable_value' => $version]
        );

        return true;
    }

    /**
     * Retrieve txAdmin configuration, secondary port, and status for this server.
     */
    public static function getTxAdminDetails(Server $server): array
    {
        $server->loadMissing(['allocations', 'allocation', 'variables', 'node']);

        $enabledVar = $server->variables->firstWhere('env_variable', 'TXADMIN_ENABLE');
        $isEnabled = ($enabledVar?->server_value ?? '1') === '1';

        // Secondary allocation is used by txAdmin
        $primaryPort = $server->allocation?->port;
        $secondaryAllocation = $server->allocations->first(fn ($a) => $a->port !== $primaryPort);

        $nodeHost = $server->node?->fqdn ?: ($server->node?->scheme . '://' . ($server->node?->fqdn ?: '127.0.0.1'));
        $nodeIp = $server->allocation?->ip ?: '127.0.0.1';

        $txPort = $secondaryAllocation?->port;
        $txUrl = $txPort ? "http://{$nodeIp}:{$txPort}" : null;

        return [
            'is_enabled' => $isEnabled,
            'primary_port' => $primaryPort,
            'txadmin_port' => $txPort,
            'txadmin_url' => $txUrl,
            'has_secondary_allocation' => $secondaryAllocation !== null,
            'server_name' => $server->name,
        ];
    }

    /**
     * Helper to read server.cfg from the server container.
     */
    protected static function readConfigFile(Server $server): string
    {
        /** @var DaemonFileRepository $repo */
        $repo = app(DaemonFileRepository::class)->setServer($server);

        try {
            return $repo->getContent('server.cfg');
        } catch (Throwable) {
            // Attempt looking in server-data/
            try {
                return $repo->getContent('server-data/server.cfg');
            } catch (Throwable) {
                return '';
            }
        }
    }

    /**
     * Helper to write server.cfg to the server container.
     */
    protected static function writeConfigFile(Server $server, string $content): void
    {
        /** @var DaemonFileRepository $repo */
        $repo = app(DaemonFileRepository::class)->setServer($server);

        try {
            $repo->putContent('server.cfg', $content);
        } catch (Throwable) {
            $repo->putContent('server-data/server.cfg', $content);
        }
    }
}
