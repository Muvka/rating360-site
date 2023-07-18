<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Models\Company\Employee;
use App\Models\Rating\Competence as RatingCompetence;
use App\Models\Statistic\Competence as StatisticCompetence;
use App\Models\Rating\CompetenceMarker;
use App\Models\Rating\MatrixTemplateClient;
use App\Models\Rating\Rating;
use App\Models\Statistic\ClientCompetence;
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
            ->get();

        return Inertia::render('Statistic/ResultsOverviewPage', [
            'title' => 'Результаты сотрудников',
            'subordinates' => $subordinates->map(function ($subordinate) {
                return [
                    'id' => $subordinate->id,
                    'name' => $subordinate->full_name,
                    'href' => route('client.statistic.results.show', $subordinate->id),
                ];
            }),
        ]);
    }

    public function create(Rating $rating, Employee $employee): Response
    {
        $this->authorize('create', [Result::class, $rating, $employee]);

        $rating->load('template');

        $competences = RatingCompetence::select('id', 'name', 'sort')
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
            ->orderBy('sort')
            ->groupBy('id')
            ->get();

        $storageKey = sprintf('rating-%s-%s-%s', Auth::user()->id, $rating->id, $employee->id);

        return Inertia::render('Rating/RatingPage', [
            'title' => 'Оценка сотрудника - '.$employee->full_name,
//            'form' => [
//                'employee' => [
//                    'id' => $employee->id,
//                    'name' => $employee->full_name,
//                ],
//                'competences' => $competences,
//            ],
            'ratingId' => $rating->id,
            'employee' => [
                'id' => $employee->id,
                'fullName' => $employee->full_name,
            ],
            'competences' => $competences,
            'storageKey' => $storageKey
        ]);
    }

    public function show(Employee $employee): Response
    {
        $this->authorize('view', [Result::class, $employee]);

        $employee->load('company');

        $competenceRatingResults = ClientCompetence::select(
            'type',
            'statistic_competence_id',
            DB::raw('cast(avg(average_rating) as decimal(3, 2)) as averageRating')
        )
            ->join('statistic_clients', 'statistic_client_competences.statistic_client_id', '=',
                'statistic_clients.id')
            ->whereHas('client.result', function (Builder $query) use ($employee) {
                $query->whereHas('rating', function (Builder $query) {
                    $query->where('status', 'closed')
                        ->latest('launched_at')
                        ->limit(1);
                })
                    ->where('company_employee_id', $employee->id);
            })
            ->with('competence')
            ->groupBy('statistic_competence_id', 'type')
            ->get()
            ->groupBy('competence.name')
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
            })
            ->values();

        $markerRatingResults = ClientCompetence::select(
            'type',
            'statistic_competence_id',
            'text',
            DB::raw('cast(avg(rating) as decimal(3, 2)) as averageRating')
        )
            ->join('statistic_clients', 'statistic_client_competences.statistic_client_id', '=', 'statistic_clients.id')
            ->join('statistic_markers', 'statistic_client_competences.id', '=', 'statistic_markers.statistic_client_competence_id')
            ->with('competence')
            ->whereHas('client.result', function (Builder $query) use ($employee) {
                $query->whereHas('rating', function (Builder $query) {
                    $query->where('status', 'closed')
                        ->latest('launched_at')
                        ->limit(1);
                })
                    ->where('company_employee_id', $employee->id);
            })
            ->groupBy('type', 'statistic_competence_id', 'text')
            ->get()
            ->groupBy('competence.name')
            ->map(function (Collection $item, string $competence) {
                return [
                    'competence' => $competence,
                    'markers' => [
                        'columns' => [
                            ['key' => 'marker', 'label' => 'Маркер'],
                            ['key' => 'outer', 'label' => 'Внешние клиенты'],
                            ['key' => 'inner', 'label' => 'Внутренние клиенты'],
                            ['key' => 'manager', 'label' => 'Руководитель'],
                            ['key' => 'self', 'label' => 'Самооценка']
                        ],
                        'data' => $item->groupBy('text')->map(function (Collection $item, string $marker) {
                            return [
                                'marker' => $marker,
                                ...$item->pluck('averageRating', 'type')
                            ];
                        })->values()
                    ],
                ];
            })
            ->values();

        $employeeFeedback = Review::select('id', 'title', 'text')
            ->whereHas('client.result', function (Builder $query) use ($employee) {
                $query->whereHas('rating', function (Builder $query) {
                    $query->where('status', 'closed')
                        ->latest('launched_at')
                        ->limit(1);
                })
                    ->where('company_employee_id', $employee->id);
            })
            ->get()
            ->groupBy('title');

        $companySummary = ClientCompetence::select(
            'statistic_competence_id',
            DB::raw('cast(avg(average_rating) as decimal(3, 2)) as averageRating')
        )
            ->with('competence')
            ->whereHas('client.result', function (Builder $query) use ($employee) {
                $query->where('company_id', $employee->company?->id);
            })
            ->whereHas('client.result.rating', function (Builder $query) {
                $query->where('status', 'closed')
                    ->latest('launched_at')
                    ->limit(1);
            })
            ->groupBy('statistic_competence_id')
            ->get()
            ->map(function (ClientCompetence $clientCompetence) {
                return [
                    'competence' => $clientCompetence->competence->name,
                    'rating' => $clientCompetence->averageRating
                ];
            });

        return Inertia::render('Statistic/ResultDetailsPage', [
            'title' => 'Отчёт по оценке 360 - '.$employee->full_name,
            'competenceRatingResults' => $competenceRatingResults,
            'markerRatingResults' => $markerRatingResults,
            'employeeFeedback' => $employeeFeedback,
            'companySummary' => $companySummary,
        ]);
    }

    public function store(Rating $rating, Employee $employee, Request $request)
    {
        $this->authorize('create', [Result::class, $rating, $employee]);

        $markers = CompetenceMarker::with('competence:id,name')
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

        list($textMarkers, $defaultMarkers) = $markers->partition(function (CompetenceMarker $marker) {
            return $marker->answer_type === 'text';
        });

        if ($textMarkers->isNotEmpty()) {
            $reviews = $textMarkers->map(function (CompetenceMarker $marker) use ($validator) {
                return new Review([
                    'title' => $marker->text,
                    'text' => $validator['marker'.$marker->id]
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
                            'rating' => $validator['marker'.$marker->id]
                        ]);
                    })
                );

                $clientCompetence->average_rating = $clientCompetence->markers->avg('rating');
                $clientCompetence->save();
            });
        }

        return redirect(route('client.rating.ratings.index'));
    }
}
