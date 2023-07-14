<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::loginUsingId(1);
//        Auth::logout();
        Filament::serving(function () {
            Filament::registerNavigationGroups([
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
            ]);
        });
    }
}
