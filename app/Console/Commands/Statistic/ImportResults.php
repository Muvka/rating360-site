<?php

namespace App\Console\Commands\Statistic;

use App\Models\Company\Employee;
use App\Models\Rating\Matrix;
use App\Models\Rating\Rating;
use App\Models\Rating\Template;
use App\Models\Statistic\ClientCompetence;
use App\Models\Statistic\Competence;
use App\Models\Statistic\Marker;
use App\Models\Statistic\Result;
use App\Models\Statistic\Client;
use App\Models\Statistic\Review;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\Console\Exception\RuntimeException;

class ImportResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistic:import-results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Экспорт результатов оценки';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $url = $this->ask('Адрес API результатов');
        $year = $this->ask('Год');
        $data = [
            'url' => $url,
            'year' => $year,
        ];
        $rules = [
            'url' => 'required|url',
            'year' => 'required|date_format:Y',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new RuntimeException('Проверьте правильность введённых данных!');
        }

        $this->info('Получение данных...');

        $client = new GuzzleClient();

        $response = $client->request('GET', $url);

        $body = $response->getBody();
        $data = json_decode($body, true);

        if ( ! $data || empty($data['result'])) {
            throw new RuntimeException('При получении данных возникла ошибка!');
        }

        $this->info('Сохранение данных...');

        DB::transaction(function () use ($data, $year) {
            $rating = $this->createRating($year);

            $results = collect($data['result']);

            $this->withProgressBar($results, function (array $result) use ($rating, $year) {
                $this->performTask(rating: $rating, result: $result, year: $year);
            });
        });
    }

    private function createRating($year): Rating
    {
        $date = Carbon::createFromDate($year, 1, 1);
        $matrix = Matrix::create([
            'name' => 'Матрица '.$year,
            'created_at' => $date,
            'updated_at' => $date
        ]);
        $template = Template::create([
            'name' => 'Шаблон '.$year,
            'created_at' => $date,
            'updated_at' => $date
        ]);
        $rating = Rating::create([
            'name' => 'Оценка '.$year,
            'rating_template_id' => $template->id,
            'rating_matrix_id' => $matrix->id,
            'status' => 'closed',
            'created_at' => $date,
            'updated_at' => $date,
            'launched_at' => $date
        ]);

        return $rating;
    }

    private function performTask($rating, $result, $year): void
    {
        if ( ! isset($result['target']) || ! isset($result['result'])) {
            return;
        }

        $date = Carbon::createFromDate($year, 1, 1);
        $clientData = [
            "Оценка внутренних клиентов" => 'inner',
            "Оценка внешних клиентов" => 'outer',
            "Оценка руководителя" => 'manager',
            "Самооценка" => 'self',
        ];

        $employee = Employee::with('directions')
            ->where('email', $result['target'])
            ->first();

        if ( ! $employee) {
            return;
        }

        $resultObject = Result::create([
            'rating_id' => $rating->id,
            'company_employee_id' => $employee->id,
            'city_id' => $employee->city_id,
            'company_id' => $employee->company_id,
            'company_division_id' => $employee->company_division_id,
            'company_subdivision_id' => $employee->company_subdivision_id,
            'company_position_id' => $employee->company_position_id,
            'company_level_id' => $employee->company_level_id,
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        if ($employee->directions) {
            $resultObject->directions()->attach($employee->directions);
        }

        foreach ($result['result'] as $competence) {
//            if ($competence['compName'] === 'Обратная связь') {
//                foreach ($competence['results'] as $title => $clients) {
//                    foreach ($clients as $type => $reviews) {
//                        $reviewObjects = [];
//                        $client = Client::firstOrCreate([
//                            'statistic_result_id' => $resultObject->id,
//                            'company_employee_id' => 1,
//                            'type' => $clientData[$type]
//                        ], [
//                            'created_at' => $date,
//                            'updated_at' => $date
//                        ]);
//
//                        foreach ($reviews as $review) {
//                            $reviewObjects[] = new Review([
//                                'title' => $title,
//                                'text' => $review,
//                                'created_at' => $date,
//                                'updated_at' => $date
//                            ]);
//                        }
//
//                        $client->reviews()->saveMany($reviewObjects);
//                    }
//                }
//            } else {
                $competenceObject = Competence::firstOrCreate([
                    'name' => $competence['compName'],
                ]);

                foreach ($competence['results'] as $marker => $clients) {
                    foreach ($clients as $type => $ratings) {
                        $client = Client::firstOrCreate([
                            'statistic_result_id' => $resultObject->id,
                            'company_employee_id' => 1,
                            'type' => $clientData[$type]
                        ], [
                            'created_at' => $date,
                            'updated_at' => $date
                        ]);

                        $clientCompetence = ClientCompetence::firstOrCreate([
                            'statistic_client_id' => $client->id,
                            'statistic_competence_id' => $competenceObject->id
                        ], [
                            'created_at' => $date,
                            'updated_at' => $date
                        ]);

                        $clientCompetence->markers()
                            ->saveManyQuietly(collect($ratings)->map(function (int $rating) use ($date, $marker) {
                                return new Marker([
                                    'text' => Str::ucfirst($marker),
                                    'rating' => $rating,
                                    'created_at' => $date,
                                    'updated_at' => $date
                                ]);
                            }));

                        $clientCompetence->average_rating = $clientCompetence->markers->avg('rating');
                        $clientCompetence->save();
                    }
                }
//            }
        }
    }
}
