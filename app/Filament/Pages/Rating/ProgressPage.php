<?php

namespace App\Filament\Pages\Rating;

use App\Models\Rating\MatrixTemplate;
use App\Models\Rating\MatrixTemplateClient;
use App\Models\Rating\Rating;
use App\Models\Statistic\Client;
use App\Models\Statistic\Result;
use Filament\Forms\Components\Card;
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

    public int $rating_id;

    public string $client;

    public int|string $count;

    public int|string $status;

    public array $data = [];

    protected array $rules = [
        'rating_id' => 'required',
        'count' => 'numeric'
    ];

    public function mount(): void
    {
        $this->columns = [
            [
                'key' => 'employee',
                'label' => 'Оцениваемый'
            ],
            [
                'key' => 'client',
                'label' => 'Оценивающий'
            ],
            [
                'key' => 'quantity',
                'label' => 'Количество'
            ],
            [
                'key' => 'status',
                'label' => 'Статус'
            ]
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
                    TextInput::make('client')
                        ->label('Оценивающий')
                        ->placeholder('Огородов'),
                    TextInput::make('count')
                        ->label('Количество')
                        ->hint('от')
                        ->placeholder('10')
                        ->numeric(),
                    Select::make('status')
                        ->label('Статус')
                        ->options([
                            '1' => 'Не завершён',
                            '2' => 'Завершён',
                        ]),
                ])
        ];
    }

    public function submit(): void
    {
        $validation = $this->validate();

        $counts = MatrixTemplateClient::select(
            'rating_matrix_template_clients.company_employee_id',
            DB::raw('count(*) as count')
        )
            ->join('rating_matrix_templates', 'rating_matrix_templates.id', '=', 'rating_matrix_template_clients.rating_matrix_template_id')
            ->join('rating_matrices', 'rating_matrices.id', '=', 'rating_matrix_templates.rating_matrix_id')
            ->join('ratings', 'ratings.rating_matrix_id', '=', 'rating_matrices.id')
            ->whereNot('status', 'closed')
            ->groupBy('rating_matrix_template_clients.company_employee_id')
            ->get()
            ->pluck('count', 'company_employee_id');

        $data = MatrixTemplate::with([
            'clients' => function (Builder $query) use ($validation) {
                $query->with('employee')
                    ->when($validation['client'], function (Builder $query, string $last_name) {
                        $query->whereHas('employee', function (Builder $query) use ($last_name) {
                            $query->where('last_name', 'LIKE', '%'.$last_name.'%');
                        });
                    });
            }, 'employee',
            'matrix.ratings.results.clients'
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
                        'quantity' => $counts->get($client->employee->id),
                        'status' => $template->matrix
                            ?->ratings
                            ?->first(fn(Rating $rating) => $rating->id === $validation['rating_id'])
                            ?->results
                            ?->first(fn(Result $result) => $result->company_employee_id === $template->company_employee_id)
                            ?->clients
                            ?->contains(function (Client $resultClient) use ($client) {
                                return (int) $resultClient->company_employee_id === (int) $client->employee->id;
                            })
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
                    if ((int)$status === 1) {
                        return ! $item['status'];
                    } elseif ((int)$status === 2) {
                        return $item['status'];
                    }
                });
            })
            ->toArray();

        $this->data = $data;
    }
}
