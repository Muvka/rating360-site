<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'shared.app.name' => config('app.name'),
            'shared.auth.user' => fn() => $request->user()
                ? $request->user()->only('fullName')
                : null,
            'shared.navigation.main' => $this->getMainNavigation()
        ]);
    }

    private function getMainNavigation(): array
    {
        $currentRouteName = Route::currentRouteName();

        $items = [
            [
                'id' => 'home',
                'label' => 'Доступные оценки',
                'href' => route('client.rating.ratings.index'),
                'isCurrent' => $currentRouteName === 'client.rating.ratings.index',
            ],
            [
                'id' => 'report',
                'label' => 'Мой отчёт',
                'href' => route('client.rating.results.show', Auth::user()?->employee?->id ?? 0),
                'isCurrent' => $currentRouteName === 'client.rating.results.show',
            ],
        ];

        if (Auth::user()?->employee?->isManager()) {
            $items[] = [
                'id' => 'manager',
                'label' => 'Результаты сотрудников',
                'href' => route('client.rating.results.index'),
                'isCurrent' => $currentRouteName === 'client.rating.results.index',
            ];
        }

        return $items;
    }
}
