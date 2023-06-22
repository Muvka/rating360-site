<?php

namespace Database\Seeders\Rating;

use App\Models\Company\Employee;
use App\Models\Rating\Rating;
use App\Models\Rating\Result;
use App\Models\Rating\ResultClient;
use Illuminate\Database\Seeder;

class ResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 1000) as $index) {
            $rating = Rating::inRandomOrder()->first();
            $employee = Employee::inRandomOrder()->first();

            $result = Result::create([
                'company_employee_id' => $employee->id,
                'rating_id' => $rating->id,
                'city' => $employee->city?->name ?? 'Киров',
                'company' => $employee->company->name,
                'division' => $employee->division?->name ?? 'Тестовый отдел',
                'subdivision' => $employee->subdivision?->name ?? 'Тестовое подразделение',
                'position' => $employee->position?->name ?? 'Тестовая должность',
                'level' => $employee->level?->name ?? 'Специалист',
            ]);

            $client = $result->clients()->create([
                'company_employee_id' => $employee->id,
                'client' => 'self',
            ]);

            $this->createMarkers($rating, $client);

            $managers = Employee::inRandomOrder()
                ->whereNot('id', $employee->id)
                ->limit(random_int(1, 2))
                ->get();

            foreach ($managers as $manager) {
                $client = $result->clients()->create([
                    'company_employee_id' => $manager->id,
                    'client' => 'manager',
                ]);

                $this->createMarkers($rating, $client);
            }

            $clients = Employee::inRandomOrder()
                ->whereNot('id', $employee->id)
                ->limit(random_int(5, 10))
                ->get();

            foreach ($clients as $client) {
                $createdClient = $result->clients()->create([
                    'rating_matrix_template_id' => $rating->matrix->templates()->inRandomOrder()->firts()->id,
                    'company_employee_id' => $client->id,
                    'client' => random_int(1, 2) === 1 ? 'inner' : 'outer',
                ]);

                $this->createMarkers($rating, $createdClient);
            }
        }
    }

    private function createMarkers(Rating $rating, ResultClient $client) {
        foreach ($rating->template->markers as $marker) {
            $client->markers()->create([
                'competence' => $marker->competence->name,
                'text' => $marker->text,
                'rating' => $marker->answer_type === 'default' && random_int(1, 5) > 1 ? random_int(1, 5) : null,
                'answer' => $marker->answer_type === 'text' ? fake('ru_RU')->text(300) : null,
            ]);
        }

        $client->avg_rating = $client->markers()->avg('rating');
        $client->save();
    }
}
