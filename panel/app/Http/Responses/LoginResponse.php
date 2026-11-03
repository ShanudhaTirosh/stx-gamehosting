<?php

namespace App\Http\Responses;

use App\Enums\CustomizationKey;
use App\Models\User;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements LoginResponseContract
{
    /**
     * Where a signed-in user belongs: admins go to the admin panel (unless they turned that off in
     * their profile), everyone else to the client panel with their servers.
     */
    public static function homeUrl(?User $user): string
    {
        if ($user && $user->getCustomization(CustomizationKey::RedirectToAdmin) && $user->canAccessPanel(Filament::getPanel('admin'))) {
            return Filament::getPanel('admin')->getUrl();
        }

        return Filament::getPanel('app')->getUrl();
    }

    public function toResponse($request): RedirectResponse|Redirector
    {
        /** @var User|null $user */
        $user = Filament::auth()->user();

        return redirect()->intended(static::homeUrl($user));
    }
}
