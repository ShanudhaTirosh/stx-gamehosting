<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$newPassword = $argv[1] ?? 'Admin@STX2026!';
$user = App\Models\User::where('username', 'admin')->orWhere('email', 'admin@pelican.local')->first();

if (!$user) {
    echo "USER_NOT_FOUND\n";
    exit(1);
}

$user->password = Illuminate\Support\Facades\Hash::make($newPassword);
$user->save();

echo "PASSWORD_RESET_SUCCESS:" . $user->username . ":" . $user->email . "\n";

