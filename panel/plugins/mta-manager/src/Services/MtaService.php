<?php

namespace BrandedHosting\MtaManager\Services;

use App\Models\Server;
use App\Repositories\Daemon\DaemonFileRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class MtaService
{
    /**
     * Determine if a server is running Multi Theft Auto.
     */
    public static function isMtaServer(?Server $server): bool
    {
        if (!$server) {
            return false;
        }

        $server->loadMissing('egg');
        if (!$server->egg) {
            return false;
        }

        $name = strtolower($server->egg->name);

        return str_contains($name, 'multi theft auto') || str_contains($name, 'mta') || $server->egg_id === 8;
    }

    /**
     * Read mtaserver.conf with cache fallback.
     */
    public static function getMtaConfig(Server $server): array
    {
        $cached = Cache::get("mta:config:{$server->id}");

        $defaults = [
            'servername' => $server->name,
            'maxplayers' => 32,
            'fpslimit' => 60,
            'voice' => 1,
            'ase' => 1,
            'httpport' => (int) ($server->allocation?->port ? $server->allocation->port + 2 : 22005),
            'password' => '',
        ];

        try {
            $content = static::readConfigFile($server);
            if (!empty($content)) {
                $parsed = static::parseXmlConfig($content);
                $merged = array_merge($defaults, $parsed);
                Cache::forever("mta:config:{$server->id}", $merged);

                return $merged;
            }
        } catch (Throwable $e) {
            Log::warning("Could not read mtaserver.conf for server {$server->id}: " . $e->getMessage());
        }

        return $cached ?? $defaults;
    }

    /**
     * Update and save mtaserver.conf.
     */
    public static function saveMtaConfig(Server $server, array $settings): bool
    {
        Cache::forever("mta:config:{$server->id}", $settings);

        try {
            $content = static::readConfigFile($server);

            if (empty($content)) {
                $content = static::getBaselineConfig($settings);
            } else {
                $content = static::updateTag($content, 'servername', htmlspecialchars($settings['servername']));
                $content = static::updateTag($content, 'maxplayers', (string) ((int) $settings['maxplayers']));
                $content = static::updateTag($content, 'fpslimit', (string) ((int) $settings['fpslimit']));
                $content = static::updateTag($content, 'voice', $settings['voice'] ? '1' : '0');
                $content = static::updateTag($content, 'ase', $settings['ase'] ? '1' : '0');
                $content = static::updateTag($content, 'httpport', (string) ((int) $settings['httpport']));
                $content = static::updateTag($content, 'password', htmlspecialchars($settings['password']));
            }

            static::writeConfigFile($server, $content);

            return true;
        } catch (Throwable $e) {
            Log::warning("Daemon offline or failed writing mtaserver.conf for server {$server->id}: " . $e->getMessage());

            return true;
        }
    }

    /**
     * Helper to extract XML tag value from configuration string.
     */
    protected static function parseXmlConfig(string $xml): array
    {
        $tags = ['servername', 'maxplayers', 'fpslimit', 'voice', 'ase', 'httpport', 'password'];
        $result = [];

        foreach ($tags as $tag) {
            if (preg_match("/<{$tag}>(.*?)<\/{$tag}>/is", $xml, $matches)) {
                $val = trim($matches[1]);
                if (in_array($tag, ['maxplayers', 'fpslimit', 'httpport'], true)) {
                    $result[$tag] = (int) $val;
                } elseif (in_array($tag, ['voice', 'ase'], true)) {
                    $result[$tag] = (int) $val;
                } else {
                    $result[$tag] = htmlspecialchars_decode($val);
                }
            }
        }

        return $result;
    }

    /**
     * Helper to update an XML tag in raw file content.
     */
    protected static function updateTag(string $xml, string $tag, string $value): string
    {
        if (preg_match("/<{$tag}>.*?<\/{$tag}>/is", $xml)) {
            return preg_replace("/<{$tag}>.*?<\/{$tag}>/is", "<{$tag}>{$value}</{$tag}>", $xml);
        }

        // Insert before closing </config>
        if (str_contains($xml, '</config>')) {
            return str_replace('</config>', "    <{$tag}>{$value}</{$tag}>\n</config>", $xml);
        }

        return $xml . "\n<{$tag}>{$value}</{$tag}>";
    }

    /**
     * Generate baseline mtaserver.conf if file does not exist yet.
     */
    protected static function getBaselineConfig(array $settings): string
    {
        $sName = htmlspecialchars($settings['servername']);
        $maxP = (int) $settings['maxplayers'];
        $fps = (int) $settings['fpslimit'];
        $voice = $settings['voice'] ? '1' : '0';
        $ase = $settings['ase'] ? '1' : '0';
        $http = (int) $settings['httpport'];
        $pass = htmlspecialchars($settings['password']);

        return <<<XML
<config>
    <servername>{$sName}</servername>
    <maxplayers>{$maxP}</maxplayers>
    <fpslimit>{$fps}</fpslimit>
    <voice>{$voice}</voice>
    <ase>{$ase}</ase>
    <httpport>{$http}</httpport>
    <password>{$pass}</password>
    <module src="mta_mysql.so" />
</config>
XML;
    }

    /**
     * Read mtaserver.conf from container.
     */
    protected static function readConfigFile(Server $server): string
    {
        /** @var DaemonFileRepository $repo */
        $repo = app(DaemonFileRepository::class)->setServer($server);

        try {
            return $repo->getContent('mods/deathmatch/mtaserver.conf');
        } catch (Throwable) {
            try {
                return $repo->getContent('mtaserver.conf');
            } catch (Throwable) {
                return '';
            }
        }
    }

    /**
     * Write mtaserver.conf to container.
     */
    protected static function writeConfigFile(Server $server, string $content): void
    {
        /** @var DaemonFileRepository $repo */
        $repo = app(DaemonFileRepository::class)->setServer($server);

        try {
            $repo->putContent('mods/deathmatch/mtaserver.conf', $content);
        } catch (Throwable) {
            $repo->putContent('mtaserver.conf', $content);
        }
    }
}
