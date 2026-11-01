<?php

$baseDir = file_exists(__DIR__ . '/vendor/autoload.php') ? __DIR__ : (file_exists('/var/www/html/vendor/autoload.php') ? '/var/www/html' : dirname(__DIR__));
require_once $baseDir . '/vendor/autoload.php';
$app = require_once $baseDir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Configuring eggs for Minecraft Modrinth & Version Changer...\n";
// 1. Update Paper
$paper = DB::table('eggs')->where('id', 1)->first();
if ($paper) {
    $features = json_decode($paper->features ?? '[]', true) ?: [];
    $tags = json_decode($paper->tags ?? '[]', true) ?: [];
    if (!in_array('modrinth_plugins', $features)) $features[] = 'modrinth_plugins';
    if (!in_array('mcversionchanger', $features)) $features[] = 'mcversionchanger';
    if (!in_array('paper', $tags)) $tags[] = 'paper';
    DB::table('eggs')->where('id', 1)->update(['features' => json_encode($features), 'tags' => json_encode($tags)]);
}

// 2. Update Purpur
$purpur = DB::table('eggs')->where('id', 2)->first();
if ($purpur) {
    $features = json_decode($purpur->features ?? '[]', true) ?: [];
    $tags = json_decode($purpur->tags ?? '[]', true) ?: [];
    if (!in_array('modrinth_plugins', $features)) $features[] = 'modrinth_plugins';
    if (!in_array('mcversionchanger', $features)) $features[] = 'mcversionchanger';
    if (!in_array('purpur', $tags)) $tags[] = 'purpur';
    DB::table('eggs')->where('id', 2)->update(['features' => json_encode($features), 'tags' => json_encode($tags)]);
}

// 3. Update Fabric
$fabric = DB::table('eggs')->where('id', 3)->first();
if ($fabric) {
    $features = json_decode($fabric->features ?? '[]', true) ?: [];
    $tags = json_decode($fabric->tags ?? '[]', true) ?: [];
    if (!in_array('modrinth_mods', $features)) $features[] = 'modrinth_mods';
    if (!in_array('mcversionchanger', $features)) $features[] = 'mcversionchanger';
    if (!in_array('fabric', $tags)) $tags[] = 'fabric';
    DB::table('eggs')->where('id', 3)->update(['features' => json_encode($features), 'tags' => json_encode($tags)]);
}

// 4. Update Forge
$forge = DB::table('eggs')->where('id', 4)->first();
if ($forge) {
    $features = json_decode($forge->features ?? '[]', true) ?: [];
    $tags = json_decode($forge->tags ?? '[]', true) ?: [];
    if (!in_array('modrinth_mods', $features)) $features[] = 'modrinth_mods';
    if (!in_array('mcversionchanger', $features)) $features[] = 'mcversionchanger';
    if (!in_array('forge', $tags)) $tags[] = 'forge';
    DB::table('eggs')->where('id', 4)->update(['features' => json_encode($features), 'tags' => json_encode($tags)]);
}

// 5. Update Bedrock
$bedrock = DB::table('eggs')->where('id', 5)->first();
if ($bedrock) {
    $features = json_decode($bedrock->features ?? '[]', true) ?: [];
    $tags = json_decode($bedrock->tags ?? '[]', true) ?: [];
    if (!in_array('mcversionchanger', $features)) $features[] = 'mcversionchanger';
    if (!in_array('bedrock', $tags)) $tags[] = 'bedrock';
    DB::table('eggs')->where('id', 5)->update(['features' => json_encode($features), 'tags' => json_encode($tags)]);
}

// 6. Update Pocketmine
$pm = DB::table('eggs')->where('id', 6)->first();
if ($pm) {
    $features = json_decode($pm->features ?? '[]', true) ?: [];
    $tags = json_decode($pm->tags ?? '[]', true) ?: [];
    if (!in_array('mcversionchanger', $features)) $features[] = 'mcversionchanger';
    if (!in_array('pocketmine', $tags)) $tags[] = 'pocketmine';
    DB::table('eggs')->where('id', 6)->update(['features' => json_encode($features), 'tags' => json_encode($tags)]);
}

// 7. Update FiveM
$fivem = DB::table('eggs')->where('id', 7)->first();
if ($fivem) {
    $tags = json_decode($fivem->tags ?? '[]', true) ?: [];
    if (!in_array('fivem', $tags)) $tags[] = 'fivem';
    if (!in_array('citizenfx', $tags)) $tags[] = 'citizenfx';
    DB::table('eggs')->where('id', 7)->update(['tags' => json_encode($tags)]);
}

echo "Configuring Node 1 for User Creatable Servers & Subdomains...\n";
$node = DB::table('nodes')->where('id', 1)->first();
if ($node) {
    $nodeTags = json_decode($node->tags ?? '[]', true) ?: [];
    if (!in_array('user_creatable_servers', $nodeTags)) {
        $nodeTags[] = 'user_creatable_servers';
    }
    DB::table('nodes')->where('id', 1)->update([
        'tags' => json_encode($nodeTags),
        'subdomain_target' => $node->subdomain_target ?: 'node1.shanutechx.com',
    ]);
}

echo "Configuring Player Counter Game Queries...\n";
if (DB::table('game_queries')->count() === 0) {
    $mcJavaId = DB::table('game_queries')->insertGetId([
        'query_type' => 'minecraft_java',
        'query_port_offset' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    foreach ([1, 2, 3, 4] as $eggId) {
        DB::table('egg_game_query')->insert(['egg_id' => $eggId, 'game_query_id' => $mcJavaId]);
    }

    $mcBedrockId = DB::table('game_queries')->insertGetId([
        'query_type' => 'minecraft_bedrock',
        'query_port_offset' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('egg_game_query')->insert(['egg_id' => 5, 'game_query_id' => $mcBedrockId]);

    $fivemId = DB::table('game_queries')->insertGetId([
        'query_type' => 'citizenfx',
        'query_port_offset' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('egg_game_query')->insert(['egg_id' => 7, 'game_query_id' => $fivemId]);
}

echo "All plugin prerequisites configured successfully in database!\n";
