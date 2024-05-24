<?php

namespace App\Services\Shared;

use App\Models\Statistic\Result;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class PrimaryNavigationService
{
    public function build(): array
    {
        $route = Route::current();

        $items = [
            [
                'icon' => 'moreCircle',
                'text' => __('navigation.primary.home'),
                'href' => route('client.rating.ratings.index'),
                'isCurrent' => $route->getName() === 'client.rating.ratings.index',
            ],
            [
                'icon' => 'document',
                'text' => __('navigation.primary.own_result'),
                'href' => route('client.statistic.results.show', Auth::user()?->id ?? 0),
                'isCurrent' => $route->getName() === 'client.statistic.results.show' && $route->parameter('employee')?->id === Auth::user()?->id,
            ],
        ];

        if (Auth::user()?->can('viewAny', Result::class)) {
            $items[] = [
                'icon' => 'users',
                'text' => __('navigation.primary.employee_results'),
                'href' => route('client.statistic.results.index'),
                'isCurrent' => $route->getName() === 'client.statistic.results.index',
            ];
        }

        $items[] = [
            'icon' => 'faq',
            'text' => __('navigation.primary.faq'),
            'href' => route('client.shared.faqs.index'),
            'isCurrent' => $route->getName() === 'client.shared.faqs.index',
        ];

        if (Auth::check() && Auth::user()->isAdmin() && Result::exists()) {
            $items[] = [
                'icon' => 'briefcase',
                'text' => __('navigation.primary.common_results'),
                'href' => route('client.statistic.general.index'),
                'separate' => true,
                'isCurrent' => $route->getName() === 'client.statistic.general.index',
            ];

            $items[] = [
                'icon' => 'activity',
                'text' => __('navigation.primary.results_by_competences'),
                'href' => route('client.statistic.competence.index'),
                'isCurrent' => $route->getName() === 'client.statistic.competence.index',
            ];

            $items[] = [
                'icon' => 'graph',
                'text' => __('navigation.primary.company_results'),
                'href' => route('client.statistic.company.index'),
                'isCurrent' => $route->getName() === 'client.statistic.company.index',
            ];

            $items[] = [
                'icon' => 'grid',
                'text' => __('navigation.primary.results_by_values'),
                'href' => route('client.statistic.value.index'),
                'isCurrent' => $route->getName() === 'client.statistic.value.index',
            ];
        }

        return $items;
    }
}
