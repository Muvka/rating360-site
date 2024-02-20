<?php

namespace App\Providers\Filament;

use App\Settings\AppGeneralSettings;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Spatie\LaravelSettings\Exceptions\MissingSettings;
use Storage;

class AdminPanelProvider extends PanelProvider
{
    /**
     * @throws \Exception
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->homeUrl('/')
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Оценка')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Компании')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Общее')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Настройки')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Система')
                    ->collapsed(),
            ])
            ->bootUsing(function (Panel $panel) {
                try {
                    $logotypeSetting = app(AppGeneralSettings::class)->logotype;
                    $logotypeUrl = isset($logotypeSetting) ? Storage::url($logotypeSetting) : '';
                } catch (MissingSettings $_) {
                    $logotypeUrl = '';
                }

                $panel->favicon($logotypeUrl);
            });
    }
}
