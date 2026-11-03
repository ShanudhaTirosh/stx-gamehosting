<?php

namespace BrandedHosting\MinecraftConfigurator\Services;

use App\Models\Server;
use App\Repositories\Daemon\DaemonFileRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class MinecraftPropertiesService
{
    /**
     * Determine if a server is running Minecraft.
     */
    public static function isMinecraftServer(?Server $server): bool
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

        return str_contains($name, 'paper')
            || str_contains($name, 'purpur')
            || str_contains($name, 'fabric')
            || str_contains($name, 'forge')
            || str_contains($name, 'bedrock')
            || str_contains($name, 'pocketmine')
            || str_contains($name, 'minecraft')
            || in_array('mcversionchanger', $features, true)
            || in_array('eula', $features, true);
    }

    /**
     * Read and parse server.properties with cache fallback.
     */
    public static function getProperties(Server $server): array
    {
        $cached = Cache::get("mc:properties:{$server->id}");

        $defaults = [
            'motd' => 'A Minecraft Server hosted by STX Enterprises',
            'gamemode' => 'survival',
            'difficulty' => 'normal',
            'online-mode' => true,
            'pvp' => true,
            'max-players' => 20,
            'view-distance' => 10,
            'simulation-distance' => 10,
            'white-list' => false,
            'hardcore' => false,
            'enable-command-block' => true,
            'allow-flight' => false,
            'spawn-protection' => 16,
        ];

        try {
            $content = static::readConfigFile($server);
            if (!empty($content)) {
                $parsed = static::parseProperties($content);
                $merged = array_merge($defaults, $parsed);
                Cache::forever("mc:properties:{$server->id}", $merged);

                return $merged;
            }
        } catch (Throwable $e) {
            Log::warning("Could not read server.properties for server {$server->id}: " . $e->getMessage());
        }

        return $cached ?? $defaults;
    }

    /**
     * Update and write server.properties.
     */
    public static function saveProperties(Server $server, array $settings): bool
    {
        Cache::forever("mc:properties:{$server->id}", $settings);

        try {
            $content = static::readConfigFile($server);
            $lines = empty($content) ? [] : explode("\n", $content);

            $mapped = [
                'motd' => $settings['motd'],
                'gamemode' => strtolower($settings['gamemode']),
                'difficulty' => strtolower($settings['difficulty']),
                'online-mode' => $settings['online-mode'] ? 'true' : 'false',
                'pvp' => $settings['pvp'] ? 'true' : 'false',
                'max-players' => (string) ((int) $settings['max-players']),
                'view-distance' => (string) ((int) $settings['view-distance']),
                'simulation-distance' => (string) ((int) $settings['simulation-distance']),
                'white-list' => $settings['white-list'] ? 'true' : 'false',
                'hardcore' => $settings['hardcore'] ? 'true' : 'false',
                'enable-command-block' => $settings['enable-command-block'] ? 'true' : 'false',
                'allow-flight' => $settings['allow-flight'] ? 'true' : 'false',
            ];

            $updatedKeys = [];
            $newLines = [];

            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (empty($trimmed) || str_starts_with($trimmed, '#')) {
                    $newLines[] = $line;
                    continue;
                }

                if (str_contains($trimmed, '=')) {
                    [$k, $v] = explode('=', $trimmed, 2);
                    $k = trim($k);
                    if (array_key_exists($k, $mapped)) {
                        $newLines[] = "{$k}={$mapped[$k]}";
                        $updatedKeys[] = $k;
                    } else {
                        $newLines[] = $line;
                    }
                } else {
                    $newLines[] = $line;
                }
            }

            // Append any missing keys
            foreach ($mapped as $k => $v) {
                if (!in_array($k, $updatedKeys, true)) {
                    $newLines[] = "{$k}={$v}";
                }
            }

            $output = implode("\n", $newLines) . "\n";
            static::writeConfigFile($server, $output);

            return true;
        } catch (Throwable $e) {
            Log::warning("Daemon offline or failed writing server.properties for server {$server->id}: " . $e->getMessage());

            return true;
        }
    }

    /**
     * Parse raw properties file.
     */
    protected static function parseProperties(string $raw): array
    {
        $result = [];
        $lines = explode("\n", $raw);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key, $val] = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val);

            if (in_array(strtolower($val), ['true', 'false'], true)) {
                $result[$key] = strtolower($val) === 'true';
            } elseif (is_numeric($val)) {
                $result[$key] = (int) $val;
            } else {
                $result[$key] = $val;
            }
        }

        return $result;
    }

    /**
     * Read server.properties from container.
     */
    protected static function readConfigFile(Server $server): string
    {
        /** @var DaemonFileRepository $repo */
        $repo = app(DaemonFileRepository::class)->setServer($server);

        return $repo->getContent('server.properties');
    }

    /**
     * Write server.properties to container.
     */
    protected static function writeConfigFile(Server $server, string $content): void
    {
        /** @var DaemonFileRepository $repo */
        $repo = app(DaemonFileRepository::class)->setServer($server);

        $repo->putContent('server.properties', $content);
    }
}

