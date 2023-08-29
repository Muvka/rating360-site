<?php

namespace App\Filament\Pages\Rating;

use App\Models\Company\Company;
use App\Models\Company\Direction;
use App\Models\Company\Division;
use App\Models\Company\Level;
use App\Models\Company\Position;
use App\Models\Company\Subdivision;
use App\Models\Rating\MatrixTemplate;
use App\Models\Rating\MatrixTemplateClient;
use App\Models\Rating\Rating;
use App\Models\Shared\City;
use App\Models\Statistic\Client;
use App\Models\Statistic\Result;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProgressPage extends Page
{
    protected static ?string $navigationGroup = 'Оценка';

    protected static ?int $navigationSort = 70;

    protected static ?string $navigationIcon = 'heroicon-o-badge-check';

    protected static string $view = 'filament.pages.rating.progress-page';

    protected static ?string $slug = 'progress';

    protected static ?string $title = 'Прогресс оценки';

    public array $columns;

    public string $rating_id;

    public ?string $city_id;

    public ?string $company_id;

    public ?string $division_id;

    public ?string $subdivision_id;

    public ?string $direction_id;

    public ?string $level_id;

    public ?string $position_id;

    public ?string $last_name;

    public int|string $count;

    public int|string $status;

    public array $data = [];

    protected array $rules = [
        'rating_id' => 'required',
        'count' => 'numeric',
    ];

    public function mount(): void
    {
        $this->columns = [
            [
                'key' => 'employee',
                'label' => 'Оцениваемый',
            ],
            [
                'key' => 'client',
                'label' => 'Оценивающий',
            ],
            [
                'key' => 'city',
                'label' => 'Город',
            ],
            [
                'key' => 'company',
                'label' => 'Компания',
            ],
            [
                'key' => 'quantity',
                'label' => 'Количество',
            ],
            [
                'key' => 'status',
                'label' => 'Статус',
            ],
        ];
    }

    public function getFormSchema(): array
    {
        return [
            Card::make()
                ->columns()
                ->schema([
                    Select::make('rating_id')
                        ->label('Оценка')
                        ->options(function () {
                            return Rating::whereNot('status', 'closed')
                                ->get()
                                ->pluck('name', 'id')
                                ->toArray();
                        }),
                    Select::make('status')
                        ->label('Статус')
                        ->options([
                            '1' => 'Не завершён',
                            '2' => 'Завершён',
                        ]),
                    Fieldset::make('Оценивающий')
                        ->columns(4)
                        ->schema([
                            TextInput::make('last_name')
                                ->label('Фамилия')
                                ->placeholder('Огородов'),
                            Select::make('city_id')
                                ->label('Город')
                                ->options(function () {
                                    return City::get()
                                        ->pluck('name', 'id')
                                        ->toArray();
                                }),
                            Select::make('company_id')
                                ->label('Компания')
                                ->options(function () {
                                    return Company::get()
                                        ->pluck('name', 'id')
                                        ->toArray();
                                }),
                            Select::make('division_id')
                                ->label('Отдел')
                                ->searchable()
                                ->getSearchResultsUsing(fn (string $search) => Division::where('name', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('name', 'id')
                                    ->toArray()
                                ),
                            Select::make('subdivision_id')
                                ->label('Подразделение')
                                ->searchable()
                                ->getSearchResultsUsing(fn (string $search) => Subdivision::where('name', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('name', 'id')
                                    ->toArray()
                                ),
                            Select::make('direction_id')
                                ->label('Направление')
                                ->searchable()
                                ->getSearchResultsUsing(fn (string $search) => Direction::where('name', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('name', 'id')
                                    ->toArray()
                                ),
                            Select::make('level_id')
                                ->label('Уровень сотрудника')
                                ->options(function () {
                                    return Level::get()
                                        ->pluck('name', 'id')
                                        ->toArray();
                                }),
                            Select::make('position_id')
                                ->label('Должность')
                                ->searchable()
                                ->getSearchResultsUsing(fn (string $search) => Position::where('name', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('name', 'id')
                                    ->toArray()
                                ),
                            TextInput::make('count')
                                ->label('Количество')
                                ->hint('от')
                                ->placeholder('10')
                                ->numeric(),
                        ]),
                ]),
        ];
    }

    public function submit(): void
    {
        $validation = $this->validate();

        $counts = MatrixTemplateClient::select([
            'rating_matrix_template_clients.company_employee_id',
            DB::raw('count(*) as count'),
        ])
            ->join('rating_matrix_templates', 'rating_matrix_templates.id', '=', 'rating_matrix_template_clients.rating_matrix_template_id')
            ->join('rating_matrices', 'rating_matrices.id', '=', 'rating_matrix_templates.rating_matrix_id')
            ->join('ratings', 'ratings.rating_matrix_id', '=', 'rating_matrices.id')
            ->whereNot('ratings.status', 'closed')
            ->whereNull('ratings.deleted_at')
            ->groupBy('rating_matrix_template_clients.company_employee_id')
            ->get()
            ->pluck('count', 'company_employee_id');

        $data = MatrixTemplate::with([
            'clients' => function (Builder $query) use ($validation) {
                $query->with(['employee', 'employee.city', 'employee.company'])
                    ->whereHas('employee', function (Builder $query) use ($validation) {
                        $query->when($validation['last_name'], function (Builder $query, string $lastName) {
                            $query->where('last_name', 'LIKE', '%'.$lastName.'%');
                        })
                            ->when($validation['city_id'], function (Builder $query, string $cityId) {
                                $query->where('city_id', $cityId);
                            })
                            ->when($validation['company_id'], function (Builder $query, string $companyId) {
                                $query->where('company_id', $companyId);
                            })
                            ->when($validation['division_id'], function (Builder $query, string $divisionId) {
                                $query->where('company_division_id', $divisionId);
                            })
                            ->when($validation['subdivision_id'], function (Builder $query, string $subdivisionId) {
                                $query->where('company_subdivision_id', $subdivisionId);
                            })
                            ->when($validation['direction_id'], function (Builder $query, string $directionId) {
                                $query->whereHas('directions', function (Builder $query) use ($directionId) {
                                    $query->where('company_direction_id', $directionId);
                                });
                            })
                            ->when($validation['level_id'], function (Builder $query, string $levelId) {
                                $query->where('company_level_id', $levelId);
                            })
                            ->when($validation['position_id'], function (Builder $query, string $positionId) {
                                $query->where('company_position_id', $positionId);
                            });
                    });
            }, 'employee',
            'matrix.ratings.results.clients',
        ])
            ->whereHas('matrix.ratings', function (Builder $query) use ($validation) {
                $query->where('id', $validation['rating_id']);
            })
            ->get()
            ->flatMap(function (MatrixTemplate $template) use ($validation, $counts) {
                return $template->clients->map(function (MatrixTemplateClient $client) use ($validation, $template, $counts) {
                    return [
                        'employee' => $template->employee->full_name,
                        'client' => $client->employee->full_name,
                        'city' => $client->employee->city?->name,
                        'company' => $client->employee->company?->name,
                        'quantity' => $counts->get($client->employee->id),
                        'status' => $template->matrix
                            ?->ratings
                            ?->first(fn (Rating $rating) => $rating->id === $validation['rating_id'])
                            ?->results
                            ?->first(fn (Result $result) => $result->company_employee_id === $template->company_employee_id)
                            ?->clients
                            ?->contains(function (Client $resultClient) use ($client) {
                                return (int) $resultClient->company_employee_id === (int) $client->employee->id;
                            }),
                    ];
                });
            })
            ->when($validation['count'], function (Collection $collection, int|string $count) {
                return $collection->filter(function (array $item) use ($count) {
                    return $item['quantity'] >= $count;
                });
            })
            ->when($validation['status'], function (Collection $collection, int|string $status) {
                return $collection->filter(function (array $item) use ($status) {
                    if ((int) $status === 1) {
                        return ! $item['status'];
                    } elseif ((int) $status === 2) {
                        return $item['status'];
                    }
                });
            })
            ->toArray();

        $this->data = $data;
    }
}
