<?php

namespace App\Http\Middleware;

use App\Models\Statistic\Result;
use App\Settings\AppGeneralSettings;
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
                ? $request->user()->only('full_name')
                : null,
            'shared.auth.accountUrl' => app(AppGeneralSettings::class)->moodle_account_url,
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
                'href' => route('client.statistic.results.show', Auth::user()?->id ?? 0),
                'isCurrent' => $route->getName() === 'client.statistic.results.show' && $route->parameter('employee')?->id === Auth::user()?->id,
            ],
        ];

        if (Auth::user()?->can('viewAny', Result::class)) {
            $items[] = [
                'icon' => 'users',
                'label' => 'Результаты сотрудников',
                'href' => route('client.statistic.results.index'),
                'isCurrent' => $route->getName() === 'client.statistic.results.index',
            ];
        }

        if (Auth::check() && Auth::user()->isAdmin() && Result::exists()) {
            $items[] = [
                'icon' => 'briefcase',
                'label' => 'Общая статистика',
                'href' => route('client.statistic.general.index'),
                'separate' => true,
                'isCurrent' => $route->getName() === 'client.statistic.general.index',
            ];

            $items[] = [
                'icon' => 'activity',
                'label' => 'Оценка по компетенциям',
                'href' => route('client.statistic.competence.index'),
                'isCurrent' => $route->getName() === 'client.statistic.competence.index',
            ];

            $items[] = [
                'icon' => 'graph',
                'label' => 'Данные по компании',
                'href' => route('client.statistic.company.index'),
                'isCurrent' => $route->getName() === 'client.statistic.company.index',
            ];

            $items[] = [
                'icon' => 'grid',
                'label' => 'Ценности',
                'href' => route('client.statistic.value.index'),
                'isCurrent' => $route->getName() === 'client.statistic.value.index',
            ];
        }

        return $items;
    }
}
