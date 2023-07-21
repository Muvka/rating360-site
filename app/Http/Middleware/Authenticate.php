<?php

namespace App\Http\Middleware;

use App\Settings\AppGeneralSettings;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : app(AppGeneralSettings::class)->moodle_account_url;
    }
}
