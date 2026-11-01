<?php

namespace BrandedHosting\Platform\Console;

use App\Enums\EggFormat;
use App\Models\Egg;
use App\Services\Eggs\Sharing\EggImporterService;
use BrandedHosting\Platform\Models\HostingProduct;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class ImportDefaultEggsCommand extends Command
{
    protected $signature = 'hosting:import-eggs';

    protected $description = 'Import official Pelican server templates (Eggs) for Minecraft, Hytale, FiveM, MTA, Valheim, and Bot runtimes.';

    protected array $eggDefinitions = [
        'mc-paper' => [
            'product_slug' => 'minecraft-paper',
            'name' => 'Paper Minecraft (Java)',
            'url' => 'https://raw.githubusercontent.com/pelican-eggs/minecraft/main/java/paper/egg-paper.yaml',
            'format' => EggFormat::YAML,
        ],
        'mc-purpur' => [
            'product_slug' => 'minecraft-paper',
            'name' => 'Purpur Minecraft',
            'url' => 'https://raw.githubusercontent.com/pelican-eggs/minecraft/main/java/purpur/egg-purpur.yaml',
            'format' => EggFormat::YAML,
        ],
        'mc-fabric' => [
            'product_slug' => 'minecraft-paper',
            'name' => 'Fabric Minecraft',
            'url' => 'https://raw.githubusercontent.com/pelican-eggs/minecraft/main/java/fabric/egg-fabric.yaml',
            'format' => EggFormat::YAML,
        ],
        'mc-forge' => [
            'product_slug' => 'minecraft-paper',
            'name' => 'Forge Minecraft',
            'url' => 'https://raw.githubusercontent.com/pelican-eggs/minecraft/main/java/forge/egg-forge-minecraft.yaml',
            'format' => EggFormat::YAML,
        ],
        'mc-bedrock' => [
            'product_slug' => 'minecraft-bedrock',
            'name' => 'Minecraft Bedrock Dedicated',
            'url' => 'https://raw.githubusercontent.com/pelican-eggs/minecraft/main/bedrock/bedrock/egg-vanilla-bedrock.json',
            'format' => EggFormat::JSON,
        ],
        'mc-pocketmine' => [
            'product_slug' => 'minecraft-bedrock',
            'name' => 'PocketMine-MP Bedrock',
            'url' => 'https://raw.githubusercontent.com/pelican-eggs/minecraft/main/bedrock/pocketmine_mp/egg-pocketmine-m-p.json',
            'format' => EggFormat::JSON,
        ],
        'fivem' => [
            'product_slug' => 'fivem-server',
            'name' => 'FiveM FXServer',
            'url' => 'https://raw.githubusercontent.com/pelican-eggs/games-standalone/main/gta/fivem/egg-five-m.json',
            'format' => EggFormat::JSON,
        ],
        'mtasa' => [
            'product_slug' => 'mtasa-server',
            'name' => 'Multi Theft Auto: San Andreas',
            'url' => 'https://raw.githubusercontent.com/pelican-eggs/games-standalone/main/gta/mtasa/egg-multi-theft-auto.json',
            'format' => EggFormat::JSON,
        ],
        'hytale' => [
            'product_slug' => 'hytale-dedicated',
            'name' => 'Hytale Dedicated Server',
            'url' => 'https://raw.githubusercontent.com/pelican-eggs/games-standalone/main/hytale/egg-hytale.yaml',
            'format' => EggFormat::YAML,
        ],
        'valheim' => [
            'product_slug' => 'valheim-server',
            'name' => 'Valheim Vanilla',
            'url' => 'https://raw.githubusercontent.com/pelican-eggs/games-steamcmd/main/valheim/valheim_vanilla/egg-valheim.json',
            'format' => EggFormat::JSON,
        ],
        'generic-nodejs' => [
            'product_slug' => 'bot-nodejs-python',
            'name' => 'Generic Node.js Bot/App',
            'url' => 'https://raw.githubusercontent.com/pelican-eggs/generic/main/nodejs/egg-nodejs-generic.yaml',
            'format' => EggFormat::YAML,
        ],
        'generic-python' => [
            'product_slug' => 'web-app-container',
            'name' => 'Generic Python Web/App',
            'url' => 'https://raw.githubusercontent.com/pelican-eggs/generic/main/python/egg-python-generic.yaml',
            'format' => EggFormat::YAML,
        ],
    ];

    public function handle(EggImporterService $importer): int
    {
        $this->info('Starting automated import of official Pelican Eggs...');

        foreach ($this->eggDefinitions as $key => $def) {
            $this->comment("Fetching [{$def['name']}] from {$def['url']}...");

            try {
                $response = Http::withHeaders(['User-Agent' => 'PelicanEggImporter'])->timeout(20)->get($def['url']);
                if (!$response->successful()) {
                    $this->error("Failed to download {$def['name']}: HTTP {$response->status()}");
                    continue;
                }

                $content = $response->body();
                
                // Check if egg with same name or author already exists to avoid duplicate constraint errors
                $egg = $importer->fromContent($content, $def['format']);

                $this->info("✔ Imported: {$egg->name} (ID: {$egg->id})");

                // Link to product if product exists
                $product = HostingProduct::where('slug', $def['product_slug'])->first();
                if ($product && empty($product->egg_id)) {
                    $product->update(['egg_id' => $egg->id]);
                    $this->line("  -> Linked to Product [{$product->name}]");
                }

            } catch (Throwable $e) {
                $this->warn("Note on {$def['name']}: " . $e->getMessage());
            }
        }

        $this->info('Egg import process finished.');

        return self::SUCCESS;
    }
}
