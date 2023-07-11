<?php

namespace App\Http\Controllers\Rating;

use App\Exports\Rating\ReportExport;
use App\Http\Controllers\Controller;
use App\Models\Company\Employee;
use App\Models\Rating\Competence;
use App\Models\Rating\Direction;
use App\Models\Rating\MatrixTemplateClient;
use App\Models\Rating\Rating;
use App\Models\Rating\Result;
use App\Models\Rating\ResultClientMarker;
use App\Models\Rating\CompetenceMarker;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class ResultController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Result::class);

        $subordinates = Employee::with('user')
            ->where('direct_manager_id', Auth::user()?->employee?->id)
            ->get();

        return Inertia::render('Company/SubordinatesOverviewPage', [
            'title' => 'Результаты сотрудников',
            'subordinates' => $subordinates->map(function ($subordinate) {
                return [
                    'id' => $subordinate->id,
                    'name' => $subordinate->user->fullName,
                    'href' => route('client.rating.results.show', $subordinate->id),
                ];
            }),
        ]);
    }

    public function create(Rating $rating, Employee $employee): Response
    {
        $this->authorize('create', [Result::class, $rating, $employee]);

        $rating->load('template');
        $employee->load('user');

        $competences = Competence::select('id', 'name', 'sort')
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

        return Inertia::render('Rating/RatingPage', [
            'title' => 'Оценка сотрудника - '.$employee->user->fullName,
            'ratingId' => $rating->id,
            'employee' => [
                'id' => $employee->id,
                'fullName' => $employee->user->fullName,
            ],
            'competences' => $competences,
        ]);
    }

    public function show(Employee $employee): Response
    {
        $this->authorize('view', [Result::class, $employee]);

        $employee->load('user', 'company');

        $results = ResultClientMarker::select(
            'rating_result_client_id',
            'competence',
            'text',
            'rating',
            'answer'
        )
            ->whereHas('client.result', function (Builder $query) use ($employee) {
                $query->whereHas('rating', function (Builder $query) {
                    $query->where('status', 'closed')
                        ->latest('launched_at')
                        ->limit(1);
                })
                    ->where('company_employee_id', $employee->id);
            })
            ->with('client:id,rating_result_id,type')
            ->whereNull('answer')
            ->get()
            ->groupBy('competence');

        $shortResults = $results->map(function (Collection $item, string $competence) {
            return collect([
                'competence' => $competence,
                'ratings' => $item->groupBy('client.type')->map(function (Collection $item) {
                    return round($item->avg('rating'), 1);
                })
            ]);
        })
            ->values()
            ->map(function (Collection $item) {
                $item['averageRating'] = round($item['ratings']->avg(), 1);
                $item['averageRatingWithoutSelf'] = round($item['ratings']->except('self')->avg(), 1);

                return $item;
            });

        $detailedResults = $results->map(function (Collection $item, string $competence) {
            return [
                'competence' => $competence,
                'markers' => $item->groupBy('text')->map(function (Collection $item, $marker) {
                    return [
                        'text' => $marker,
                        'ratings' => $item->groupBy('client.type')->map(function ($item) {
                            $rating = round($item->avg('rating'), 2);
                            return $rating ?: '?';
                        }),
                    ];
                })->values(),
            ];
        })
            ->values();

        $employeeFeedback = ResultClientMarker::select('id', 'text', 'answer')
            ->whereHas('client.result', function (Builder $query) use ($employee) {
                $query->where('company_employee_id', $employee->id);
            })
            ->whereNotNull('answer')
            ->get()
            ->groupBy('text');

        $companySummary = ResultClientMarker::select(
            'competence',
            DB::raw('cast(avg(rating) as decimal(3, 2)) as averageRating')
        )
            ->whereHas('client.result', function (Builder $query) use ($employee) {
                $query->where('company', $employee->company->name);
            })
            ->whereHas('client.result.rating', function (Builder $query) {
                $query->where('status', 'closed')
                    ->latest('launched_at')
                    ->limit(1);
            })
            ->whereNotNull('rating')
            ->groupBy('competence')
            ->get();

        return Inertia::render('Rating/ReportPage', [
            'title' => 'Отчёт по оценке 360 - '.$employee->user->fullName,
            'exportRoute' => route('client.rating.results.export', $employee->id),
            'companySummary' => $companySummary,
            'employeeFeedback' => $employeeFeedback,
            'shortResults' => $shortResults,
            'detailedResults' => $detailedResults,
        ]);
    }

    public function store(Rating $rating, Employee $employee, Request $request)
    {
        $this->authorize('create', [Result::class, $rating, $employee]);

        $markers = CompetenceMarker::whereHas('competence.templates.ratings', function (Builder $query) use ($rating) {
            $query->where('id', $rating->id);
        })
            ->get();

        $employee->load(
            'city:id,name',
            'company:id,name',
            'division:id,name',
            'subdivision:id,name',
            'position:id,name',
            'level:id,name',
            'directions:id,name',
        );

        $client = MatrixTemplateClient::select('rating_matrix_template_id', 'company_employee_id', 'type')
            ->where('company_employee_id', Auth::user()?->employee?->id)
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

        $result = Result::where('rating_id', $rating->id)
            ->where('company_employee_id', $employee->id)
            ->first();

        if ( ! $result) {
            $result = Result::create([
                'rating_id' => $rating->id,
                'company_employee_id' => $employee->id,
                'city' => $employee->city?->name ?: 'Без города',
                'company' => $employee->company?->name ?: 'Без компании',
                'division' => $employee->division?->name ?: 'Без отдела',
                'subdivision' => $employee->subdivision?->name ?: 'Без подразделения',
                'position' => $employee->position?->name ?: 'Без должности',
                'level' => $employee->level?->name ?: 'Без уровня',
            ]);
        }

        if ($employee->directions->isNotEmpty()) {
            $employee->directions->each(function ($direction) use ($result) {
                $resultDirection = Direction::firstOrCreate(['name' => $direction->name]);
                $result->directions()->attach($resultDirection);
            });
        }

        $client = $result->clients()->firstOrCreate([
            'company_employee_id' => $client->company_employee_id,
            'type' => $client->type,
        ]);

        $client->markers()->delete();

        foreach ($validator as $key => $answer) {
            $markerId = Str::replace('marker', '', $key);

            $marker = $markers->first(function ($marker) use ($markerId) {
                    return $markerId === (string) $marker->id;
                });

            if ( ! $marker) {
                continue;
            }

            $client->markers()->create([
                'competence' => $marker->competence->name,
                'value' => $marker->value?->name,
                'text' => $marker->text,
                'rating' => $this->getScore($answer),
                'answer' => $marker->answer_type === 'text' ? $answer : null,
            ]);
        }

        return redirect(route('client.rating.ratings.index'));
    }

    public function export(Employee $employee)
    {
        $employee->load('user', 'company');

        $fileName = sprintf('%s-%s.xlsx', $employee->user->fullName, $employee->company->name);

        $results = ResultClientMarker::select(
            'rating_result_client_id',
            'competence',
            'text',
            'rating',
            'answer'
        )
            ->whereHas('client.result', function (Builder $query) use ($employee) {
                $query->whereHas('rating', function (Builder $query) {
                    $query->where('status', 'closed')
                        ->latest('launched_at')
                        ->limit(1);
                })
                    ->where('company_employee_id', $employee->id);
            })
            ->with('client:id,rating_result_id,type')
            ->whereNull('answer')
            ->get()
            ->groupBy('competence');

        $shortResults = $results->map(function (Collection $item, string $competence) {
            return collect([
                'competence' => $competence,
                'ratings' => $item->groupBy('client.type')->map(function (Collection $item) {
                    return round($item->avg('rating'), 1);
                })
            ]);
        })
            ->values()
            ->map(function (Collection $item) {
                $item['averageRating'] = round($item['ratings']->avg(), 1);
                $item['averageRatingWithoutSelf'] = round($item['ratings']->except('self')->avg(), 1);

                return $item;
            });

        $detailedResults = $results->map(function (Collection $item, string $competence) {
            return [
                'competence' => $competence,
                'markers' => $item->groupBy('text')->map(function (Collection $item, $marker) {
                    return [
                        'text' => $marker,
                        'ratings' => $item->groupBy('client.type')->map(function ($item) {
                            $rating = round($item->avg('rating'), 2);
                            return $rating ?: '?';
                        }),
                    ];
                })->values(),
            ];
        })
            ->values();

        $employeeFeedback = ResultClientMarker::select('id', 'text', 'answer')
            ->whereHas('client.result', function (Builder $query) use ($employee) {
                $query->where('company_employee_id', $employee->id);
            })
            ->whereNotNull('answer')
            ->get()
            ->values()
            ->groupBy('text')
            ->flatten();

        $export = new ReportExport([
            'competences' => $shortResults->toArray(),
            'markers' => $detailedResults->toArray(),
            'feedback' => $employeeFeedback->toArray(),
        ]);

        return Excel::download($export, $fileName);
    }

    private function getScore($answer): int|null
    {
        $ratingScore = null;

        switch ($answer) {
            case 'always':
            {
                $ratingScore = 5;
                break;
            }

            case 'often':
            {
                $ratingScore = 4;
                break;
            }

            case 'sometimes':
            {
                $ratingScore = 3;
                break;
            }

            case 'rarely':
            {
                $ratingScore = 2;
                break;
            }

            case 'never':
            {
                $ratingScore = 1;
                break;
            }
        }

        return $ratingScore;
    }
}
