<?php

namespace App\Http\Middleware;

use App\Models\Rating\Result;
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
        $route = Route::current();

        $items = [
            [
                'icon' => 'moreCircle',
                'label' => 'Доступные оценки',
                'href' => route('client.rating.ratings.index'),

                'isCurrent' => $route->getName() === 'client.rating.ratings.index',
            ],
            [
                'icon' => 'document',
                'label' => 'Мой отчёт',
                'href' => route('client.rating.results.show', Auth::user()?->employee?->id ?? 0),
                'isCurrent' => $route->getName() === 'client.rating.results.show' && $route->parameter('employee')?->id === Auth::user()?->employee?->id,
            ],
        ];

        if (Auth::user()?->can('viewAny', Result::class)) {
            $items[] = [
                'icon' => 'users',
                'label' => 'Результаты сотрудников',
                'href' => route('client.rating.results.index'),
                'isCurrent' => $route->getName() === 'client.rating.results.index',
            ];
        }

//        if (Auth::user()?->isAdmin()) {
        $items[] = [
            'icon' => 'briefcase',
            'label' => 'Общая статистика',
            'href' => route('client.rating.statistics.general'),
            'separate' => true,
            'isCurrent' => $route->getName() === 'client.rating.statistics.general',
        ];

        $items[] = [
            'icon' => 'activity',
            'label' => 'Оценка по компетенциям',
            'href' => route('client.rating.statistics.competence'),
            'isCurrent' => $route->getName() === 'client.rating.statistics.competence',
        ];

        $items[] = [
            'icon' => 'graph',
            'label' => 'Данные по компании',
            'href' => route('client.rating.statistics.company'),
            'isCurrent' => $route->getName() === 'client.rating.statistics.company',
        ];

        $items[] = [
            'icon' => 'grid',
            'label' => 'Ценности',
            'href' => route('client.rating.statistics.value'),
            'isCurrent' => $route->getName() === 'client.rating.statistics.value',
        ];
//        }

        return $items;
    }
}
