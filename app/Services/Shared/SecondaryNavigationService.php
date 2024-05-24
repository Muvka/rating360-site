<?php

namespace App\Services\Shared;

use App\Settings\Shared\GeneralSettings;
use Illuminate\Support\Facades\Auth;

class SecondaryNavigationService
{
    public function __construct(private readonly GeneralSettings $generalSettings)
    {
    }

    public function build(): array
    {
        $items = [];

        if (Auth::check() && Auth::user()->isAdmin()) {
            $items[] = [
                'text' => __('navigation.secondary.admin'),
                'href' => route('filament.admin.pages.dashboard'),
            ];
        }

        if ($this->generalSettings->moodle_auth_enabled && $this->generalSettings->moodle_account_url) {
            $items[] = [
                'text' => __('navigation.secondary.account'),
                'href' => $this->generalSettings->moodle_account_url,
            ];
        }

        if (! auth()->check()) {
            $items[] = [
                'text' => __('navigation.secondary.login'),
                'href' => route('client.user.login.show'),
            ];
        } else {
            $items[] = [
                'text' => __('navigation.secondary.logout'),
                'href' => route('client.user.logout.logout'),
            ];
        }

        return $items;
    }
}
