<?php

namespace App\Http\Middleware;

use App\Settings\AppGeneralSettings;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        $appGeneralSettings = app(AppGeneralSettings::class);

        if ($request->expectsJson()) {
            return null;
        }

        if ($appGeneralSettings->moodle_auth_enabled) {
            return $appGeneralSettings->moodle_account_url;
        }

        return route('client.user.login.show');
    }
}
