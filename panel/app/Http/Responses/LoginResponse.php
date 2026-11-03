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
     * Where a signed-in user belongs: admins go to the admin panel (/admin),
     * customers go to the client panel (/app).
     */
    public static function homeUrl(?User $user): string
    {
        if (! $user) {
            return Filament::getPanel('app')->getUrl() . '/servers';
        }

        if ($user->isRootAdmin() || ($user->canAccessPanel(Filament::getPanel('admin')) && $user->getCustomization(CustomizationKey::RedirectToAdmin, true))) {
            return Filament::getPanel('admin')->getUrl();
        }

        return Filament::getPanel('app')->getUrl() . '/servers';
    }

    public function toResponse($request): RedirectResponse|Redirector
    {
        /** @var User|null $user */
        $user = Filament::auth()->user();
        $target = static::homeUrl($user);

        // Admins must go directly to /admin
        if ($user && ($user->isRootAdmin() || ($user->canAccessPanel(Filament::getPanel('admin')) && $user->getCustomization(CustomizationKey::RedirectToAdmin, true)))) {
            session()->forget('url.intended');

            return redirect()->to(Filament::getPanel('admin')->getUrl());
        }

        // For customers: only preserve intended URL if it is a genuine client resource
        $intended = session()->get('url.intended');
        if ($intended) {
            $path = parse_url($intended, PHP_URL_PATH) ?? '';
            if (str_starts_with($path, '/app/customer-orders') || str_starts_with($path, '/server/') || str_starts_with($path, '/app/order/')) {
                return redirect()->intended($target);
            }
            session()->forget('url.intended');
        }

        return redirect()->to(Filament::getPanel('app')->getUrl() . '/servers');
    }
}
