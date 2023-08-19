<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Models\Company\Employee;
use App\Models\Rating\Competence as RatingCompetence;
use App\Models\Rating\CompetenceMarker;
use App\Models\Rating\MatrixTemplateClient;
use App\Models\Rating\Rating;
use App\Models\Statistic\Client;
use App\Models\Statistic\ClientCompetence;
use App\Models\Statistic\Competence as StatisticCompetence;
use App\Models\Statistic\Marker;
use App\Models\Statistic\Result;
use App\Models\Statistic\Review;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class ResultController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Result::class);
        $subordinates = Employee::where('direct_manager_id', Auth::user()?->id)
            ->orWhere('functional_manager_id', Auth::user()?->id)
            ->get()
            ->map(function (Employee $subordinate) {
                return [
                    'id' => $subordinate->id,
                    'name' => $subordinate->full_name,
                    'href' => route('client.statistic.results.show', $subordinate->id),
                ];
            });
        $merged = $subordinates->merge(Employee::with('managerAccess')
            ->whereHas('managerAccess', function (Builder $query) {
                $query->where('manager_id', Auth::user()?->id);
            })
            ->get()
            ->flatMap(function (Employee $subordinate) {
                return $subordinate->managerAccess->map(function (Employee $subordinate) {
                    return [
                        'id' => $subordinate->id,
                        'name' => $subordinate->full_name,
                        'href' => route('client.statistic.results.show', $subordinate->id),
                    ];
                });
            }));

        return Inertia::render('Statistic/ResultsOverviewPage', [
            'title' => 'Результаты сотрудников',
            'subordinates' => $merged->sortBy('name')->values(),
        ]);
    }

    public function create(Rating $rating, Employee $employee): Response
    {
        $this->authorize('create', [Result::class, $rating, $employee]);

        $rating->load('template');

        $competences = RatingCompetence::select('id', 'name', 'manager_only', 'sort')
            ->with('markers', function (Builder $query) {
                $query->select(
                    'id',
                    'rating_competence_id',
                    'text',
                    'answer_type',
                    'sort'
                )
                    ->orderBy('sort');
            })
            ->whereHas('templates', function (Builder $query) use ($rating) {
                $query->where('rating_templates.id', $rating->template->id);
            })
            ->when(! $employee->isManager(), function (Builder $query) {
                $query->where('manager_only', false);
            })
            ->orderBy('sort')
            ->groupBy('id')
            ->get();

        $storageKey = sprintf('rating-%s-%s-%s', Auth::user()->id, $rating->id, $employee->id);

        return Inertia::render('Rating/RatingPage', [
            'title' => 'Оценка сотрудника - '.$employee->full_name,
            'ratingId' => $rating->id,
            'employee' => [
                'id' => $employee->id,
                'fullName' => $employee->full_name,
            ],
            'competences' => $competences,
            'storageKey' => $storageKey,
        ]);
    }

    public function show(Employee $employee): Response
    {
        $this->authorize('view', [Result::class, $employee]);

        $employee->load('company');

        $years = Rating::select(DB::raw('YEAR(launched_at) as year'))
            ->whereHas('results', function (Builder $query) use ($employee) {
                $query->where('company_employee_id', $employee->id);
            })
            ->where('status', 'closed')
            ->orWhere('show_results_before_completion', true)
            ->latest('year')
            ->groupBy('year')
            ->get()
            ->pluck('year');

        $competenceRatingResults = ClientCompetence::select([
            'type',
            'statistic_competences.name as competence',
            DB::raw('YEAR(launched_at) as launched_year'),
            DB::raw('cast(avg(average_rating) as decimal(3, 2)) as averageRating'),
        ])
            ->join('statistic_competences', 'statistic_competences.id', '=', 'statistic_client_competences.statistic_competence_id')
            ->join('statistic_clients', 'statistic_client_competences.statistic_client_id', '=',
                'statistic_clients.id')
            ->join('statistic_results', 'statistic_results.id', '=',
                'statistic_clients.statistic_result_id')
            ->join('ratings', 'ratings.id', '=', 'statistic_results.rating_id')
            ->where('statistic_results.company_employee_id', $employee->id)
            ->whereNull('ratings.deleted_at')
            ->where(function (Builder $query) {
                $query->where('ratings.status', 'closed')
                    ->orWhere('ratings.show_results_before_completion', true);
            })
            ->groupBy('launched_year', 'competence', 'type')
            ->get()
            ->groupBy('launched_year')
            ->map(function (Collection $collection) {
                return $collection->groupBy('competence')
                    ->map(function (Collection $item, string $competence) {
                        $clients = $item->mapWithKeys(function (ClientCompetence $client) {
                            return [$client['type'] => $client['averageRating']];
                        });

                        return [
                            'competence' => $competence,
                            'averageRating' => $clients->avg(),
                            'averageRatingWithoutSelf' => $clients->except('self')->avg(),
                            'clients' => $clients,
                        ];
                    });
            });

        $markerRatingResults = ClientCompetence::select(
            'type',
            'statistic_competences.name as competence',
            'text',
            DB::raw('YEAR(launched_at) as launched_year'),
            DB::raw('cast(avg(rating) as decimal(3, 2)) as averageRating')
        )
            ->join('statistic_competences', 'statistic_competences.id', '=', 'statistic_client_competences.statistic_competence_id')
            ->join('statistic_markers', 'statistic_client_competences.id', '=', 'statistic_markers.statistic_client_competence_id')
            ->join('statistic_clients', 'statistic_client_competences.statistic_client_id', '=', 'statistic_clients.id')
            ->join('statistic_results', 'statistic_results.id', '=',
                'statistic_clients.statistic_result_id')
            ->join('ratings', 'ratings.id', '=', 'statistic_results.rating_id')
            ->where('statistic_results.company_employee_id', $employee->id)
            ->whereNull('ratings.deleted_at')
            ->where(function (Builder $query) {
                $query->where('ratings.status', 'closed')
                    ->orWhere('ratings.show_results_before_completion', true);
            })
            ->groupBy('launched_year', 'type', 'competence', 'text')
            ->get()
            ->groupBy('launched_year')
            ->map(function (Collection $collection) {
                return $collection->groupBy('competence')
                    ->map(function (Collection $item, string $competence) {
                        return [
                            'competence' => $competence,
                            'markers' => [
                                'columns' => [
                                    ['key' => 'marker', 'label' => 'Маркер'],
                                    ['key' => 'outer', 'label' => 'Внешние клиенты'],
                                    ['key' => 'inner', 'label' => 'Внутренние клиенты'],
                                    ['key' => 'manager', 'label' => 'Руководитель'],
                                    ['key' => 'self', 'label' => 'Самооценка'],
                                ],
                                'data' => $item->groupBy('text')->map(function (Collection $item, string $marker) {
                                    return [
                                        'marker' => $marker,
                                        ...$item->pluck('averageRating', 'type'),
                                    ];
                                })->values(),
                            ],
                        ];
                    });
            });

        $reviews = Review::select(['id', 'statistic_client_id', 'title', 'text'])
            ->whereHas('client.result', function (Builder $query) use ($employee) {
                $query->whereHas('rating', function (Builder $query) {
                    $query->where('status', 'closed')
                        ->orWhere('show_results_before_completion', true);
                })
                    ->where('company_employee_id', $employee->id);
            })
            ->with([
                'client.result.rating' => function (Builder $query) {
                    $query->select(['id', DB::raw('YEAR(launched_at) as launched_year')]);
                },
            ])
            ->get()
            ->groupBy('client.result.rating.launched_year')
            ->map(function (Collection $collection) {
                return $collection->groupBy('title');
            });

        $companySummary = ClientCompetence::select([
            'statistic_competences.name as competence',
            DB::raw('YEAR(launched_at) as launched_year'),
            DB::raw('cast(avg(average_rating) as decimal(3, 2)) as averageRating'),
        ])
            ->join('statistic_competences', 'statistic_competences.id', '=', 'statistic_client_competences.statistic_competence_id')
            ->join('statistic_clients', 'statistic_client_competences.statistic_client_id', '=', 'statistic_clients.id')
            ->join('statistic_results', 'statistic_results.id', '=',
                'statistic_clients.statistic_result_id')
            ->join('ratings', 'ratings.id', '=', 'statistic_results.rating_id')
            ->where('statistic_results.company_id', $employee->company?->id)
            ->whereNull('ratings.deleted_at')
            ->where(function (Builder $query) {
                $query->where('ratings.status', 'closed')
                    ->orWhere('ratings.show_results_before_completion', true);
            })
            ->groupBy('launched_year', 'competence')
            ->get()
            ->groupBy('launched_year')
            ->map(function (Collection $collection) {
                return $collection->map(function (ClientCompetence $clientCompetence) {
                    return [
                        'competence' => $clientCompetence->competence,
                        'rating' => $clientCompetence->averageRating,
                    ];
                });
            });

        if ($years) {
            foreach ($years as $year) {
                $resultsByYear[] = [
                    'id' => 'tab-'.$year,
                    'title' => $year,
                    'competence' => isset($competenceRatingResults[$year]) ? $competenceRatingResults[$year]->values()
                        ->toArray() : [],
                    'marker' => isset($markerRatingResults[$year]) ? $markerRatingResults[$year]->values()
                        ->toArray() : [],
                    'reviews' => $reviews[$year] ?? [],
                    'company' => $companySummary[$year] ?? [],
                ];
            }
        }

        $comparisonData = ClientCompetence::select([
            'statistic_competences.name as competence',
            DB::raw('YEAR(launched_at) as launched_year'),
            DB::raw('cast(avg(average_rating) as decimal(3, 2)) as averageRating'),
        ])
            ->join('statistic_competences', 'statistic_competences.id', '=', 'statistic_client_competences.statistic_competence_id')
            ->join('statistic_clients', 'statistic_clients.id', '=', 'statistic_client_competences.statistic_client_id')
            ->join('statistic_results', 'statistic_results.id', '=', 'statistic_clients.statistic_result_id')
            ->join('ratings', 'ratings.id', '=', 'statistic_results.rating_id')
            ->where('statistic_results.company_employee_id', $employee->id)
            ->whereNull('ratings.deleted_at')
            ->where(function (Builder $query) {
                $query->where('ratings.status', 'closed')
                    ->orWhere('ratings.show_results_before_completion', true);
            })
            ->oldest('launched_year')
            ->groupBy('competence', 'launched_year')
            ->get()
            ->groupBy('competence')
            ->map(function (Collection $collection, string $competence) {
                return [
                    'competence' => $competence,
                    ...$collection->groupBy('launched_year')->mapWithKeys(function (Collection $collection, string $year) {
                        return ['rating-'.$year => $collection->first()->averageRating];
                    }),
                ];
            });

        if ($comparisonData) {
            $corporateValues = Marker::select(
                DB::raw('YEAR(launched_at) as launched_year'),
                DB::raw('cast(avg(rating) as decimal(3, 2)) as average_rating')
            )
                ->join('statistic_client_competences', 'statistic_client_competences.id', '=', 'statistic_markers.statistic_client_competence_id')
                ->join('statistic_clients', 'statistic_clients.id', '=', 'statistic_client_competences.statistic_client_id')
                ->join('statistic_results', 'statistic_results.id', '=', 'statistic_clients.statistic_result_id')
                ->join('ratings', 'ratings.id', '=', 'statistic_results.rating_id')
                ->where('statistic_results.company_employee_id', $employee->id)
                ->whereNull('ratings.deleted_at')
                ->where(function (Builder $query) {
                    $query->where('ratings.status', 'closed')
                        ->orWhere('ratings.show_results_before_completion', true);
                })
                ->oldest('launched_year')
                ->groupBy('launched_year')
                ->whereNotNull('rating_value_id')
                ->get()
                ->flatMap(function (Marker $marker) {
                    return ['rating-'.$marker->launched_year => $marker->average_rating];
                })
                ->put('competence', 'Корпоративные ценности');

            if ($corporateValues) {
                $comparisonData->when($comparisonData->get('Корпоративные ценности'), function (Collection $collection, array $value) use ($corporateValues) {
                    $collection->put('Корпоративные ценности', collect($value)->merge(collect($corporateValues)));
                }, function (Collection $collection) use ($corporateValues) {
                    $collection->put('Корпоративные ценности', $corporateValues);
                });
            }

            $ratingComparison = [
                'columns' => [
                    ['key' => 'competence', 'label' => 'Компетенция'],
                    ...$years->reverse()->map(function (string $year) {
                        return ['key' => 'rating-'.$year, 'label' => $year.' год'];
                    }),
                ],
                'data' => $comparisonData->values(),
            ];
        } else {
            $ratingComparison = [];
        }

        return Inertia::render('Statistic/ResultDetailsPage', [
            'title' => 'Отчёт по оценке 360 - '.$employee->full_name,
            'resultsByYear' => $resultsByYear,
            'ratingComparison' => $ratingComparison,
            'progressText' => $this->getProgressText($employee),
        ]);
    }

    public function store(Rating $rating, Employee $employee, Request $request)
    {
        $this->authorize('create', [Result::class, $rating, $employee]);

        $markers = CompetenceMarker::with('competence:id,name')
            ->whereHas('competence', function (Builder $query) use ($employee) {
                $query->when(! $employee->isManager(), function (Builder $query) {
                    $query->where('manager_only', false);
                });
            })
            ->whereHas('competence.templates.ratings', function (Builder $query) use ($rating) {
                $query->where('id', $rating->id);
            })
            ->get();

        $employee->load(
            'city:id',
            'company:id',
            'division:id',
            'subdivision:id',
            'position:id',
            'level:id',
            'directions:id',
        );

        $client = MatrixTemplateClient::select('rating_matrix_template_id', 'company_employee_id', 'type')
            ->where('company_employee_id', Auth::user()?->id)
            ->whereHas('template', function (Builder $query) use ($employee) {
                $query->where('company_employee_id', $employee->id);
            })
            ->firstOrFail();

        $validateData = $markers->reduce(function (array $carry, CompetenceMarker $marker) {
            if ($marker->answer_type === 'default') {
                $carry['rules']['marker'.$marker->id] = 'required';
                $carry['messages']['marker'.$marker->id] = 'Нужно выбрать один из вариантов';
            } else {
                $carry['rules']['marker'.$marker->id] = 'required|max:65535';
                $carry['messages']['marker'.$marker->id] = 'Поле обязательно для заполнения';
            }

            return $carry;
        }, [
            'rules' => [],
            'messages' => [],
        ]);

        $validator = Validator::make($request->all(), $validateData['rules'], $validateData['messages'])->validate();

        $result = Result::firstOrCreate([
            'rating_id' => $rating->id,
            'company_employee_id' => $employee->id,
            'city_id' => $employee->city?->id,
            'company_id' => $employee->company?->id,
            'company_division_id' => $employee->division?->id,
            'company_subdivision_id' => $employee->subdivision?->id,
            'company_position_id' => $employee->position?->id,
            'company_level_id' => $employee->level?->id,
        ]);

        $result->directions()->sync($employee->directions);

        $client = $result->clients()->firstOrCreate([
            'company_employee_id' => $client->company_employee_id,
            'type' => $client->type,
        ]);

        $client->clientCompetences()->delete();
        $client->reviews()->delete();

        [$textMarkers, $defaultMarkers] = $markers->partition(function (CompetenceMarker $marker) {
            return $marker->answer_type === 'text';
        });

        if ($textMarkers->isNotEmpty()) {
            $reviews = $textMarkers->map(function (CompetenceMarker $marker) use ($validator) {
                return new Review([
                    'title' => $marker->text,
                    'text' => $validator['marker'.$marker->id],
                ]);
            });

            $client->reviews()->saveMany($reviews);
        }

        if ($defaultMarkers->isNotEmpty()) {
            $defaultMarkersByCompetence = $defaultMarkers->groupBy('competence.name');

            $defaultMarkersByCompetence->each(function (Collection $markers, string $key) use ($client, $validator) {
                $competence = StatisticCompetence::firstOrCreate(['name' => $key]);

                $clientCompetence = ClientCompetence::create([
                    'statistic_client_id' => $client->id,
                    'statistic_competence_id' => $competence->id,
                ]);

                $clientCompetence->markers()->saveManyQuietly(
                    $markers->map(function (CompetenceMarker $marker) use ($validator) {
                        return new Marker([
                            'rating_value_id' => $marker->rating_value_id,
                            'text' => $marker->text,
                            'rating' => $validator['marker'.$marker->id],
                        ]);
                    })
                );

                $clientCompetence->average_rating = $clientCompetence->markers->avg('rating');
                $clientCompetence->save();
            });
        }

        return redirect(route('client.rating.ratings.index'));
    }

    private function getProgressText(Employee $employee): string
    {
        $rating = Rating::select(['ratings.id', 'status', 'launched_at'])
            ->whereIn('status', ['in progress', 'paused'])
            ->whereHas('results', function (Builder $query) use ($employee) {
                $query->where('company_employee_id', $employee->id);
            })
            ->latest('launched_at')
            ->first();

        if (! $rating) {
            return '';
        }

        $matrixClients = MatrixTemplateClient::select('company_employee_id')
            ->whereHas('template.matrix.ratings', function (Builder $query) use ($rating) {
                $query->where('id', $rating->id);
            })
            ->whereHas('template', function (Builder $query) use ($employee) {
                $query->where('company_employee_id', $employee->id);
            })
            ->get();

        $totalClients = $matrixClients->count();
        $finishedClients = Client::whereIn('company_employee_id', $matrixClients->pluck('company_employee_id'))
            ->whereHas('result', function (Builder $query) use ($employee, $rating) {
                $query->whereHas('rating', function (Builder $query) use ($rating) {
                    $query->where('id', $rating->id);
                })
                    ->where('company_employee_id', $employee->id);
            })
            ->count();

        return sprintf('Оценили %s из %s', $finishedClients, $totalClients);
    }
}
