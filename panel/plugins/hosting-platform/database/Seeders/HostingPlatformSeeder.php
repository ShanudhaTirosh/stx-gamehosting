<?php

namespace Database\Seeders;

use App\Models\Egg;
use BrandedHosting\Platform\Enums\BillingPeriod;
use BrandedHosting\Platform\Enums\PaymentMethodType;
use BrandedHosting\Platform\Models\HostingCategory;
use BrandedHosting\Platform\Models\HostingPaymentMethod;
use BrandedHosting\Platform\Models\HostingPlan;
use BrandedHosting\Platform\Models\HostingProduct;
use Illuminate\Database\Seeder;

class HostingPlatformSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Sri Lankan Payment Methods
        HostingPaymentMethod::firstOrCreate(
            ['code' => 'bank_transfer'],
            [
                'name' => 'Direct Bank Transfer',
                'type' => PaymentMethodType::BankTransfer,
                'description' => 'Transfer via CDM or Online Banking (Commercial / Sampath / BOC / HNB)',
                'recipient_name' => 'Pelican Hosting Lanka (Pvt) Ltd',
                'account_or_mobile_number' => '1000847291',
                'bank_name' => 'Commercial Bank of Ceylon PLC',
                'branch_name' => 'Colombo Fort (Branch Code 001)',
                'payment_instructions' => "1. Transfer the exact order total to the account above.\n2. In the transfer remarks/reference, enter your Order Number.\n3. Take a clear photo of the bank slip or download the PDF/screenshot from your banking app.\n4. Upload the proof on this page for fast manual verification.",
                'requires_proof_upload' => true,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        HostingPaymentMethod::firstOrCreate(
            ['code' => 'ez_cash'],
            [
                'name' => 'Dialog eZ Cash',
                'type' => PaymentMethodType::EzCash,
                'description' => 'Instant mobile wallet transfer via USSD #111# or eZ Cash App',
                'recipient_name' => 'Pelican Host Merchant',
                'account_or_mobile_number' => '0771234567',
                'payment_instructions' => "1. Dial #111# on your Dialog, Hutch, or Airtel mobile, or open the eZ Cash App.\n2. Select 'Send Money' / 'Merchant Pay'.\n3. Enter Mobile Number: 0771234567.\n4. Enter the exact order amount in LKR and your PIN.\n5. Copy the 10-digit Txn ID and upload a screenshot of the confirmation SMS.",
                'requires_proof_upload' => true,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        HostingPaymentMethod::firstOrCreate(
            ['code' => 'helapay'],
            [
                'name' => 'Helakuru HelaPay',
                'type' => PaymentMethodType::HelaPay,
                'description' => 'Instant bank transfer using HelaPay LANKAQR or VPA',
                'recipient_name' => 'pelicanhost@helapay',
                'account_or_mobile_number' => 'pelicanhost@helapay',
                'payment_instructions' => "1. Open the Helakuru App and tap HelaPay.\n2. Choose 'Pay via VPA' and enter: pelicanhost@helapay.\n3. Enter the exact Order Total.\n4. Confirm transfer with biometric/PIN.\n5. Enter the reference number and attach the in-app receipt screenshot.",
                'requires_proof_upload' => true,
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        // Find fallback Egg if available
        $defaultEgg = Egg::first();

        // 2. Categories & Products
        $mcCat = HostingCategory::firstOrCreate(
            ['slug' => 'minecraft'],
            [
                'name' => 'Minecraft Hosting',
                'description' => 'High-frequency Minecraft Java & Bedrock hosting powered by Paper, Fabric, Forge and Purpur.',
                'icon' => 'tabler-brand-minecraft',
                'badge_text' => 'High TPS Guarantee',
                'sort_order' => 1,
                'is_visible' => true,
            ]
        );

        $mcProduct = HostingProduct::firstOrCreate(
            ['slug' => 'minecraft-paper'],
            [
                'category_id' => $mcCat->id,
                'name' => 'Minecraft Java (Paper / Fabric)',
                'description' => 'Optimized for high-player survival, minigames, and modpacks with instant version changer.',
                'icon' => 'tabler-brand-minecraft',
                'egg_id' => $defaultEgg?->id,
                'deployment_tags' => ['game-node'],
                'ports' => ['25565'],
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        HostingPlan::firstOrCreate(
            ['slug' => 'mc-iron-4gb'],
            [
                'product_id' => $mcProduct->id,
                'name' => 'Iron Tier (4GB)',
                'tagline' => 'Best for SMP and 15-30 players',
                'price_lkr' => 2800.00,
                'billing_period' => BillingPeriod::Monthly,
                'memory_mb' => 4096,
                'cpu_percent' => 200,
                'disk_mb' => 30720,
                'database_limit' => 2,
                'backup_limit' => 3,
                'is_featured' => true,
                'is_available' => true,
                'sort_order' => 1,
            ]
        );

        HostingPlan::firstOrCreate(
            ['slug' => 'mc-diamond-8gb'],
            [
                'product_id' => $mcProduct->id,
                'name' => 'Diamond Tier (8GB)',
                'tagline' => 'Heavy modpacks, Fabric, and large communities',
                'price_lkr' => 5200.00,
                'billing_period' => BillingPeriod::Monthly,
                'memory_mb' => 8192,
                'cpu_percent' => 300,
                'disk_mb' => 61440,
                'database_limit' => 3,
                'backup_limit' => 5,
                'is_featured' => false,
                'is_available' => true,
                'sort_order' => 2,
            ]
        );

        // Hytale
        $hytaleCat = HostingCategory::firstOrCreate(
            ['slug' => 'hytale'],
            [
                'name' => 'Hytale Dedicated',
                'description' => 'Dedicated adventure and creative servers for the upcoming sandbox RPG with QUIC/UDP transport.',
                'icon' => 'tabler-sword',
                'badge_text' => 'Pre-Release Ready',
                'sort_order' => 2,
                'is_visible' => true,
            ]
        );

        $hytaleProduct = HostingProduct::firstOrCreate(
            ['slug' => 'hytale-dedicated'],
            [
                'category_id' => $hytaleCat->id,
                'name' => 'Hytale Dedicated Server',
                'description' => 'High single-core CPU compute with compact object headers and automated world backups.',
                'icon' => 'tabler-sword',
                'egg_id' => $defaultEgg?->id,
                'deployment_tags' => ['game-node'],
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        HostingPlan::firstOrCreate(
            ['slug' => 'hytale-adventurer'],
            [
                'product_id' => $hytaleProduct->id,
                'name' => 'Adventurer (6GB)',
                'tagline' => 'Dedicated world exploration with friends',
                'price_lkr' => 4200.00,
                'billing_period' => BillingPeriod::Monthly,
                'memory_mb' => 6144,
                'cpu_percent' => 250,
                'disk_mb' => 40960,
                'database_limit' => 1,
                'backup_limit' => 4,
                'is_featured' => true,
                'is_available' => true,
                'sort_order' => 1,
            ]
        );

        // FiveM
        $fivemCat = HostingCategory::firstOrCreate(
            ['slug' => 'fivem'],
            [
                'name' => 'FiveM Hosting',
                'description' => 'GTA V roleplay and freeroam servers with pre-configured txAdmin and MariaDB databases.',
                'icon' => 'tabler-car',
                'badge_text' => 'txAdmin Included',
                'sort_order' => 3,
                'is_visible' => true,
            ]
        );

        $fivemProduct = HostingProduct::firstOrCreate(
            ['slug' => 'fivem-server'],
            [
                'category_id' => $fivemCat->id,
                'name' => 'FiveM FXServer',
                'description' => 'Dual-port allocation (Game + txAdmin) with high RAM capacity for complex ESX / QBCore scripts.',
                'icon' => 'tabler-car',
                'egg_id' => $defaultEgg?->id,
                'deployment_tags' => ['game-node'],
                'ports' => ['30120', '40120'],
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        HostingPlan::firstOrCreate(
            ['slug' => 'fivem-starter'],
            [
                'product_id' => $fivemProduct->id,
                'name' => 'Roleplay Starter (6GB)',
                'tagline' => 'Supports up to 48 players with QBCore / ESX',
                'price_lkr' => 4800.00,
                'billing_period' => BillingPeriod::Monthly,
                'memory_mb' => 6144,
                'cpu_percent' => 300,
                'disk_mb' => 51200,
                'database_limit' => 2,
                'backup_limit' => 3,
                'is_featured' => true,
                'is_available' => true,
                'sort_order' => 1,
            ]
        );

        // MTA:SA
        $mtaCat = HostingCategory::firstOrCreate(
            ['slug' => 'mta'],
            [
                'name' => 'MTA:SA Hosting',
                'description' => 'Multi Theft Auto: San Andreas hosting with low latency, full resource manager, and HTTP webport.',
                'icon' => 'tabler-steering-wheel',
                'sort_order' => 4,
                'is_visible' => true,
            ]
        );

        $mtaProduct = HostingProduct::firstOrCreate(
            ['slug' => 'mtasa-server'],
            [
                'category_id' => $mtaCat->id,
                'name' => 'MTA:SA Server',
                'description' => 'Dedicated game port (22003) and HTTP webport (22005) with libzstd Linux runtime support.',
                'icon' => 'tabler-steering-wheel',
                'egg_id' => $defaultEgg?->id,
                'deployment_tags' => ['game-node'],
                'ports' => ['22003', '22005'],
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        HostingPlan::firstOrCreate(
            ['slug' => 'mta-classic'],
            [
                'product_id' => $mtaProduct->id,
                'name' => 'San Andreas Classic (2GB)',
                'tagline' => 'Freeroam & Drift server setup',
                'price_lkr' => 1800.00,
                'billing_period' => BillingPeriod::Monthly,
                'memory_mb' => 2048,
                'cpu_percent' => 150,
                'disk_mb' => 20480,
                'database_limit' => 1,
                'backup_limit' => 2,
                'is_featured' => false,
                'is_available' => true,
                'sort_order' => 1,
            ]
        );

        // Bot Hosting
        $botCat = HostingCategory::firstOrCreate(
            ['slug' => 'bots'],
            [
                'name' => 'Discord, Telegram & WhatsApp Bots',
                'description' => 'Always-online 24/7 bot containers for Node.js, Python, and Go with GitHub auto-deploy on boot.',
                'icon' => 'tabler-robot',
                'badge_text' => '24/7 Online',
                'sort_order' => 5,
                'is_visible' => true,
            ]
        );

        $botProduct = HostingProduct::firstOrCreate(
            ['slug' => 'bot-nodejs-python'],
            [
                'category_id' => $botCat->id,
                'name' => 'Generic Bot Container (Node / Python / Go)',
                'description' => 'Isolated cgroup container with persistent volume storage and environment secrets support.',
                'icon' => 'tabler-robot',
                'egg_id' => $defaultEgg?->id,
                'deployment_tags' => ['bot-worker'],
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        HostingPlan::firstOrCreate(
            ['slug' => 'bot-lite-1gb'],
            [
                'product_id' => $botProduct->id,
                'name' => 'Bot Lite (1GB)',
                'tagline' => 'Perfect for music bots, moderation, and chat automation',
                'price_lkr' => 950.00,
                'billing_period' => BillingPeriod::Monthly,
                'memory_mb' => 1024,
                'cpu_percent' => 75,
                'disk_mb' => 10240,
                'database_limit' => 1,
                'backup_limit' => 2,
                'is_featured' => true,
                'is_available' => true,
                'sort_order' => 1,
            ]
        );

        // Web Hosting
        $webCat = HostingCategory::firstOrCreate(
            ['slug' => 'web-hosting'],
            [
                'name' => 'Web & Application Hosting',
                'description' => 'Deploy Node.js, Python Flask/FastAPI, or static websites with custom domain support and SSL.',
                'icon' => 'tabler-world',
                'sort_order' => 6,
                'is_visible' => true,
            ]
        );

        $webProduct = HostingProduct::firstOrCreate(
            ['slug' => 'web-app-container'],
            [
                'category_id' => $webCat->id,
                'name' => 'Web Application Hosting',
                'description' => 'Containerized web application deployment with reverse proxy port binding.',
                'icon' => 'tabler-world',
                'egg_id' => $defaultEgg?->id,
                'deployment_tags' => ['web-ingress'],
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        HostingPlan::firstOrCreate(
            ['slug' => 'web-standard-2gb'],
            [
                'product_id' => $webProduct->id,
                'name' => 'Web Standard (2GB)',
                'tagline' => 'For APIs, portals, and web services',
                'price_lkr' => 1800.00,
                'billing_period' => BillingPeriod::Monthly,
                'memory_mb' => 2048,
                'cpu_percent' => 100,
                'disk_mb' => 20480,
                'database_limit' => 1,
                'backup_limit' => 2,
                'is_featured' => false,
                'is_available' => true,
                'sort_order' => 1,
            ]
        );
    }
}
