<?php

namespace App\Http\Controllers\Rating;

use App\Http\Controllers\Controller;
use App\Models\Company\Employee;
use App\Models\Rating\Competence;
use App\Models\Rating\MatrixTemplateClient;
use App\Models\Rating\Rating;
use App\Models\Rating\Result;
use App\Models\Rating\TemplateMarker;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Inertia\Response;

class RatingController extends Controller
{
    public function showForm(Rating $rating, Employee $employee): Response
    {
//        $rating = Rating::select('id', 'rating_template_id')
//            ->with('template:id')
//            ->where('id', $ratingId)
//            ->where('status', 'in progress')
//            ->firstOrFail();

//        if (Auth::user()->cannot('view', $rating)) {
//            abort(404);
//        }

//        $employeeRaw = Employee::select('id', 'user_id')
//            ->with('user:id,first_name,last_name,middle_name')->findOrFail($employeeId);
        $employee = [
            'id' => $employee->id,
            'fullName' => $employee->user->fullName,
        ];

        $competences = Competence::select('id', 'name', 'sort')
            ->with('markers', function (Builder $query) use ($rating) {
                $query->select(
                    'id',
                    'rating_competence_id',
                    'rating_template_id',
                    'text',
                    'answer_type',
                    'sort'
                )
                    ->where('rating_template_id', $rating->template->id)
                    ->orderBy('sort');
            }
            )
            ->whereHas('markers', function (Builder $query) use ($rating) {
                $query->select('id', 'rating_competence_id', 'rating_template_id')
                    ->where('rating_template_id', $rating->template->id);
            })
            ->orderBy('sort')
            ->groupBy('id')
            ->get();

        return Inertia::render('Rating/RatingPage', [
            'title' => 'Оценка сотрудника - '.$employee['fullName'],
            'ratingId' => $rating->id,
            'employee' => $employee,
            'competences' => $competences,
        ]);
    }

    public function saveResult(string $ratingId, string $employeeId, Request $request)
    {
        $rating = Rating::select('id', 'rating_template_id')
            ->with('template', function (Builder $query) {
                $query->select('id')
                    ->with('markers', function (Builder $query) {
                        $query->select(
                            'id',
                            'rating_template_id',
                            'rating_competence_id',
                            'rating_value_id',
                            'text',
                            'answer_type'
                        )
                            ->with('competence:id,name', 'value:id,name');
                    });
            })
            ->where('id', $ratingId)
            ->where('status', 'in progress')
            ->firstOrFail();

        $employee = Employee::with(
            'city:id,name',
            'company:id,name',
            'division:id,name',
            'subdivision:id,name',
            'position:id,name',
            'level:id,name',
        )->findOrFail($employeeId);

        $client = MatrixTemplateClient::select('rating_matrix_template_id', 'company_employee_id', 'type')
            ->where('company_employee_id', Auth::user()?->employee?->id)
            ->whereHas('template', function (Builder $query) use ($employee) {
                $query->where('company_employee_id', $employee->id);
            })
            ->firstOrFail();

        $validateData = $rating->template->markers->reduce(function (array $carry, TemplateMarker $marker) {
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
                'city' => $employee->city->name,
                'company' => $employee->company->name,
                'division' => $employee->division->name,
                'subdivision' => $employee->subdivision->name,
                'position' => $employee->position->name,
                'level' => $employee->level->name,
            ]);
        }

        $client = $result->clients()->create([
            'company_employee_id' => $client->company_employee_id,
            'type' => $client->type,
        ]);

        foreach ($validator as $key => $answer) {
            $markerId = Str::replace('marker', '', $key);

            $marker = $rating
                ->template
                ->markers
                ->first(function ($marker) use ($markerId) {
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

        return redirect(route('client.shared.home'));
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
