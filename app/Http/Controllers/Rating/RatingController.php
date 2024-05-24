<?php

namespace App\Http\Controllers\Rating;

use App\Http\Controllers\Controller;
use App\Models\Rating\Rating;
use App\Models\Statistic\Client;
use App\Settings\Shared\GeneralSettings;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RatingController extends Controller
{
    public function index(GeneralSettings $settings)
    {
        $ratings = Rating::with([
            'matrixTemplates' => function (Builder $query) {
                $query->whereHas('clients')
                    ->with([
                        'clients' => function (Builder $query) {
                            $employeeId = Auth::user()?->id ?? 0;

                            $query->with('employee')
                                ->where('company_employee_id', $employeeId);
                        },
                    ]);
            },
        ])
            ->where('status', 'in progress')
            ->get()
            ->flatMap(function ($rating) {
                return $rating->matrixTemplates->flatMap(function ($matrixTemplate) use ($rating) {
                    return $matrixTemplate->clients->map(function ($client) use ($rating, $matrixTemplate) {
                        if ($client->type === 'self') {
                            $title = 'Cамооценка';
                        } else {
                            $title = 'Оценка 360 сотрудника: '.$matrixTemplate->employee->full_name;
                        }

                        $isCompleted = Client::with('result')
                            ->whereHas('result', function (Builder $query) use ($rating, $matrixTemplate) {
                                $query->where('rating_id', $rating->id)
                                    ->where('company_employee_id', $matrixTemplate->company_employee_id);
                            })
                            ->where('company_employee_id', $client->company_employee_id)
                            ->exists();

                        return [
                            'id' => $rating->id.$client->id,
                            'title' => $title,
                            'isCompleted' => $isCompleted,
                            'href' => route('client.statistic.results.create', [
                                $rating->id,
                                $matrixTemplate->company_employee_id,
                            ]),
                        ];
                    });
                });
            });

        return Inertia::render('Rating/RatingsOverviewPage', [
            'title' => 'Доступные оценки',
            'instruction' => [
                'text' => $settings->instruction_text,
                'video' => $settings->instruction_video,
            ],
            'ratings' => $ratings,
        ]);
    }
}
