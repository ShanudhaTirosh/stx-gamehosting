<?php

use App\Http\Controllers\Auth\OAuthController;
use Illuminate\Support\Facades\Route;

// The client panel's login page lives under its own path (APP_CLIENT_PATH), so keep /login working for
// old links, emails and OAuth failures by forwarding it there. With the panel at the site root,
// Filament serves /login itself.
if (config('panel.client_path', 'app') !== '') {
    Route::get('/login', fn () => redirect()->route('filament.app.auth.login'))->name('auth.login');
} else {
    Route::redirect('/login', '/login')->name('auth.login');
}

Route::prefix('oauth')->group(function () {
    Route::get('/redirect/{driver}', [OAuthController::class, 'redirect'])->name('auth.oauth.redirect');
    Route::get('/callback/{driver}', [OAuthController::class, 'callback'])->name('auth.oauth.callback')->withoutMiddleware('guest');
});
