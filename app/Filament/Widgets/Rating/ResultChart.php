<?php

namespace App\Filament\Widgets\Rating;

use App\Models\Rating\Result;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ResultChart extends ApexChartWidget
{
    protected static string $chartId = 'resultChart';

    protected static ?string $heading = 'Результаты оценки';

    protected int|string|array $columnSpan = 2;

//    protected static ?string $pollingInterval = null;

    protected function getFormSchema(): array
    {
        return [
            Select::make('rating_id')
                ->label('Оценка')
                ->options(fn() => Result::with('rating')
                    ->get()
                    ->pluck('rating.name', 'rating.id')
                )
                ->multiple(),
//            Select::make('employee')
//                ->label('Cотрудник')
//                ->searchable()
//                ->getSearchResultsUsing(
//                    fn(string $search) => Result::with('user')
//                        ->whereHas('user', function (Builder $query) use ($search) {
//                            $query->where('last_name', 'like', "%{$search}%");
//                        })
//                        ->limit(20)
//                        ->get()
//                        ->pluck('user.full_name', 'employee.id'))
//                ->getOptionLabelUsing(fn($value): ?string => Result::find($value)
//                    ?->user
//                    ->full_name)
//                ->options(
//                    fn() => Result::with('employee')
//                        ->get()
//                        ->pluck('employee.user.full_name', 'employee.user_id')
//                ),
            Select::make('city')
                ->label('Город')
                ->options(
                    fn() => Result::select('city')
                        ->distinct('city')
                        ->pluck('city', 'city')
                ),
            Select::make('company')
                ->label('Компания')
                ->options(
                    fn() => Result::select('company')
                        ->distinct('company')
                        ->pluck('company', 'company')
                ),
            Select::make('division')
                ->label('Отдел')
                ->options(
                    fn() => Result::select('division')
                        ->distinct('division')
                        ->pluck('division', 'division')
                ),
            Select::make('subdivision')
                ->label('Подразделение')
                ->options(
                    fn() => Result::select('subdivision')
                        ->distinct('subdivision')
                        ->pluck('subdivision', 'subdivision')
                )
                ->searchable(),
            Select::make('level')
                ->label('Уровень сотрудника')
                ->options(
                    fn() => Result::select('level')
                        ->distinct('level')
                        ->pluck('level', 'level')
                ),
        ];
    }

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $rating_ids = $this->filterFormData['rating_id'];
        $city = $this->filterFormData['city'];
        $company = $this->filterFormData['company'];
        $division = $this->filterFormData['division'];
        $subdivision = $this->filterFormData['subdivision'];
        $level = $this->filterFormData['level'];

        if ( ! empty($rating_ids)) {
            $query = Result::select(
                DB::raw('cast(avg(rt_rating_result_client_markers.rating) as decimal(5, 4)) as avg_rating'),
                'rating_results.rating_id',
            )
                ->join(
                    'rating_result_clients',
                    'rating_results.id',
                    '=',
                    'rating_result_clients.rating_result_id'
                )
                ->join(
                    'rating_result_client_markers',
                    'rating_result_clients.id',
                    '=',
                    'rating_result_client_markers.rating_result_client_id'
                )
                ->with('rating')
                ->whereIn('rating_results.rating_id', $rating_ids)
                ->groupBy('rating_results.rating_id');
        } else {
            $query = Result::select(
                DB::raw('cast(avg(rt_rating_result_client_markers.rating) as decimal(5, 4)) as avg_rating'),
                'rating_result_clients.client',
            )
                ->join(
                    'rating_result_clients',
                    'rating_results.id',
                    '=',
                    'rating_result_clients.rating_result_id'
                )
                ->join(
                    'rating_result_client_markers',
                    'rating_result_clients.id',
                    '=',
                    'rating_result_client_markers.rating_result_client_id'
                )
                ->groupBy('rating_result_clients.client');
        }

        if ($city) {
            $query->where('city', $city);
        }

        if ($company) {
            $query->where('company', $company);
        }

        if ($division) {
            $query->where('division', $division);
        }

        if ($subdivision) {
            $query->where('subdivision', $subdivision);
        }

        if ($level) {
            $query->where('level', $level);
        }

//        dd($query->get());
        if ( ! empty($rating_ids)) {
            $result = $query->get()->pluck('avg_rating', 'rating.name');

            $xaxis = [
                'categories' => $result->keys(),
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ];
        } else {
            $result = $query->get()->pluck('avg_rating', 'type');

            $xaxis = [
                'categories' => ['Самооценка', 'Руководители', 'Внунтренние', 'Внешние'],
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ];
        }

//        dd($result);


//        if ($city === 'Киров') {
//            $xaxis = [
//                'categories' => ['01-01-2022', '01-01-2023', '01-01-2024'],
//                'labels' => [
//                    'style' => [
//                        'colors' => '#9ca3af',
//                        'fontWeight' => 600,
//                    ],
//                ],
//            ];
//        } else {
//            $xaxis = [
//                'categories' => ['Самооценка', 'Руководители', 'Внунтренние', 'Внешние'],
//                'labels' => [
//                    'style' => [
//                        'colors' => '#9ca3af',
//                        'fontWeight' => 600,
//                    ],
//                ],
//            ];
//        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 500,
            ],
            'series' => [
                [
                    'name' => 'Средняя оценка',
                    'data' => ! empty($rating_ids) ? $result->values() : [
                        $result['self'] ?? 0,
                        $result['manager'] ?? 0,
                        $result['inner'] ?? 0,
                        $result['outer'] ?? 0
                    ],
                ],
            ],
            'xaxis' => $xaxis,
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'colors' => ['#089000'],
        ];
    }
}
