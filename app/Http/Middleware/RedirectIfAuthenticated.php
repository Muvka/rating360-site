<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use App\Settings\AppGeneralSettings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;
        $appGeneralSettings = app(AppGeneralSettings::class);

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        if ($appGeneralSettings->moodle_auth_enabled && $appGeneralSettings->moodle_account_url) {
            return redirect()->away($appGeneralSettings->moodle_account_url);
        }

        return $next($request);
    }
}
