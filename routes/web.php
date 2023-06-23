<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function (\App\Settings\AppGeneralSettings $settings) {
    return \Inertia\Inertia::render('Shared/HomePage', [
        'title' => '????',
        'instruction' => [
            'text' => $settings->instruction_text,
            'video' => $settings->instruction_video,
        ],
        'ratings' => [
            [
                'id' => '1',
                'title' => 'Оценка 360 сотрудника: Александра Безденежных',
                'isCompleted' => true,
                'href' => route('client.shared.rating'),
            ],
            [
                'id' => '2',
                'title' => 'Оценка 360 сотрудника: Ольга Валова',
                'isCompleted' => true,
                'href' => route('client.shared.rating'),
            ],
            [
                'id' => '3',
                'title' => 'Оценка 360 сотрудника: Наталья Воробьёва',
                'isCompleted' => true,
                'href' => route('client.shared.rating'),
            ],
            [
                'id' => '4',
                'title' => 'Оценка 360 сотрудника: Кристина Лузянина',
                'isCompleted' => false,
                'href' => route('client.shared.rating'),
            ],
            [
                'id' => '5',
                'title' => 'Оценка 360 сотрудника: Дмитрий Николаев',
                'isCompleted' => false,
                'href' => route('client.shared.rating'),
            ],
            [
                'id' => '6',
                'title' => 'Оценка 360: Самооценка',
                'isCompleted' => true,
                'href' => route('client.shared.rating'),
            ],
        ],
    ]);
})->name('client.shared.home');

Route::get('/report', function () {
    return \Inertia\Inertia::render('Rating/RatingReportPage', [
        'title' => 'Отчёт по оценке 360 - Огородов Кирилл',
        'ratingResults' => \App\Models\Rating\Competence::with('markers')
            ->whereHas('markers')
            ->orderBy('sort')
            ->get()
            ->map(function ($competence) {
                $markers = $competence->markers->map(function ($marker) {
                    return [
                        'id' => (string) $marker->id,
                        'text' => $marker->text,
                        'ratings' => [
                            'inner' => random_int(100, 500) / 100,
                            'outer' => random_int(100, 500) / 100,
                            'manager' => random_int(100, 500) / 100,
                            'self' => random_int(100, 500) / 100,
                        ],
                    ];
                });

                $averageRating = collect([
                    'inner' => $markers->avg('ratings.inner'),
                    'outer' => $markers->avg('ratings.outer'),
                    'manager' => $markers->avg('ratings.manager'),
                    'self' => $markers->avg('ratings.self'),
                ]);

                return [
                    'id' => (string) $competence->id,
                    'competence' => $competence->name,
                    'averageRating' => $averageRating->flatten()->avg(),
                    'averageRatingWithoutSelf' => $averageRating->except('self')->flatten()->avg(),
                    'averageRatingByClient' => $averageRating,
                    'markers' => $markers,
                ];
            }),
        'companySummary' => \App\Models\Rating\Competence::orderBy('sort')
            ->get()
            ->map(function ($competence) {
                return [
                    'id' => (string) $competence->id,
                    'competence' => $competence->name,
                    'rating' => random_int(100, 500) / 100,
                ];
            }),
        'employeeFeedback' => [
            'positives' => [
                'Управление проектами и коммуникациями с членами команды проекта.',
                'Выполнять задания качественно, в срок; применять полученные знания, брать ответственность за выполненную работу',
                'Не обращать внимания на раздражители',
                'Сохранение спокойствия и верный подход при сборе проектной информации',
                'Ответственность',
                'Администрирование процессов',
                'Администрировать работу, держать ситуацию под контролем',
            ],
            'negatives' => [
                'Контроль подрядчиков и выполнения условия договора',
                'Требовательность в отношении выполнения заданий коллегами, публичные выступления перед коллегами',
                'Лидерство',
                'Развивать компетенции в направлении 1с и поиске подрядчиков для проектов',
                'Инициативность',
                'Коммуникации, обратная связь, постановка задач',
                'Прокачать уверенность и лидерство',
                'Управленческие компетенции',
            ],
        ],
    ]);
})->name('client.shared.report');

Route::get('/test2', function () {
    return \Inertia\Inertia::render('Rating/SubordinatesOverviewPage', [
        'title' => 'Результаты сотрудников',
        'subordinates' => [
            [
                'id' => '1',
                'name' => 'Александра Безденежных',
                'href' => route('client.shared.report'),
            ],
            [
                'id' => '2',
                'name' => 'Ольга Валова',
                'href' => route('client.shared.report'),
            ],
            [
                'id' => '3',
                'name' => 'Наталья Воробьёва',
                'href' => route('client.shared.report'),
            ],
            [
                'id' => '4',
                'name' => 'Кристина Лузянина',
                'href' => route('client.shared.report'),
            ],
            [
                'id' => '5',
                'name' => 'Дмитрий Николаев',
                'href' => route('client.shared.report'),
            ],
        ]
    ]);
})->name('client.shared.test2');

Route::get('/rating', function () {
    $templateId = 1;
//    $template = \App\Models\Rating\Template::with('markers')->find(1);
//dd($template->markers, $template->markers->filter(function ($marker) {
//    return $marker->answer_type === 'default';
//})->map(function ($marker) {
//    return [
//        'marker' . $marker->id => 'required'
//    ];
//})->collapse()->toArray());
    return \Inertia\Inertia::render('Rating/RatingPage', [
        'title' => 'Оценка сотрудника - Ольга Валова',
        'employeeName' => 'Ольга Валова',
        'competences' => \App\Models\Rating\Competence::select('id', 'name', 'sort')
            ->with('markers', function ($query) use ($templateId) {
                $query->select('id', 'rating_competence_id', 'rating_template_id', 'text', 'answer_type', 'sort')
                    ->where('rating_template_id', $templateId)
                    ->orderBy('sort');
            }
            )
            ->whereHas('markers', function ($query) use ($templateId) {
                $query->select('id', 'rating_competence_id', 'rating_template_id')
                    ->where('rating_template_id', $templateId);
            })
            ->orderBy('sort')
            ->groupBy('id')
            ->get(),
    ]);
})->name('client.shared.rating');

Route::post('/rating', function (Request $request) {
    $template = \App\Models\Rating\Template::with('markers')->find(1);

    $validator = Validator::make($request->all(), $template->markers->filter(function ($marker) {
        return $marker->answer_type === 'default';
    })->map(function ($marker) {
        return [
            'marker' . $marker->id => 'required'
        ];
    })->collapse()->toArray(), [
        '*' => 'Нужно выбрать один из вариантов'
    ])->validate();

    dd($validator);

    return redirect(route('client.shared.home'));
})->name('client.shared.rating.store');
