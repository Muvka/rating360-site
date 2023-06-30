<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Rating\Rating;
use App\Models\Rating\ResultClient;
use App\Settings\AppGeneralSettings;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(AppGeneralSettings $settings): Response
    {
        $ratings = Rating::with([
            'matrixTemplates' => function (Builder $query) {
                $query->whereHas('clients')
                    ->with([
                        'clients' => function (Builder $query) {
                            $employeeId = Auth::user()?->employee?->id ?? 0;

                            $query->with('user')
                                ->where('company_employee_id', $employeeId);
                        }
                    ]);
            }
        ])
            ->where('status', 'in progress')
            ->get()
            ->flatMap(function ($rating) {
                return $rating->matrixTemplates->flatMap(function ($matrixTemplate) use ($rating) {
                    return $matrixTemplate->clients->map(function ($client) use ($rating, $matrixTemplate) {
                        if ($client->type === 'self') {
                            $title = 'Cамооценка';
                        } else {
                            $title = 'Оценка 360 сотрудника: '.$matrixTemplate->employee->user->fullName;
                        }

                        $isCompleted = ResultClient::with('result')
                            ->whereHas('result', function (Builder $query) use ($rating) {
                                $query->where('rating_id', $rating->id);
                            })
                            ->where('company_employee_id', $client->company_employee_id)
                            ->exists();

                        return [
                            'id' => $rating->id . $client->id,
                            'title' => $title,
                            'isCompleted' => $isCompleted,
                            'href' => route('client.rating.rating.showForm', [
                                'ratingId' => $rating->id,
                                'employeeId' => $matrixTemplate->company_employee_id,
                            ])
                        ];
                    });
                });
            });

        return Inertia::render('Shared/HomePage', [
            'title' => 'Главная страница',
            'instruction' => [
                'text' => $settings->instruction_text,
                'video' => $settings->instruction_video,
            ],
            'ratings' => $ratings,
        ]);
    }
}
