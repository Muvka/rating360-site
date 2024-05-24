<?php

namespace App\Http\Middleware;

use App\Services\Shared\PrimaryNavigationService;
use App\Services\Shared\SecondaryNavigationService;
use App\Settings\Shared\GeneralSettings;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Spatie\LaravelSettings\Exceptions\MissingSettings;
use Storage;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        try {
            $logotypeSetting = app(GeneralSettings::class)->logotype;
            $logotypeUrl = isset($logotypeSetting) ? Storage::url($logotypeSetting) : '';
        } catch (MissingSettings $_) {
            $logotypeUrl = '';
        }

        return array_merge(parent::share($request), [
            'shared.app.name' => config('app.name'),
            'shared.app.logotype' => $logotypeUrl,
            'shared.auth.user' => fn () => $request->user()
                ? $request->user()->only('full_name')
                : null,
            'shared.navigation.main' => (new PrimaryNavigationService())->build(),
            'shared.navigation.secondary' => app(SecondaryNavigationService::class)->build(),
        ]);
    }
}
