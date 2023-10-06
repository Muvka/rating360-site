<?php

namespace App\Console\Commands\Company;

use App\Models\Company\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Exception\RuntimeException;

class SyncEmployeesSourceId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:sync-employees-source-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Синхронизация внешнего идентификатора сотрудников';

    private function validateInput(string $url, string $property): void
    {
        $rules = [
            'url' => 'required|url',
            'property' => 'required',
        ];

        $validator = Validator::make([
            'url' => $url,
            'property' => $property,
        ], $rules);

        if ($validator->fails()) {
            throw new RuntimeException('Проверьте правильность введённых данных!');
        }
    }

    private function sync(Collection $items, string $property): void
    {
        $this->info('Синхронизация...');

        DB::transaction(function () use ($items, $property) {
            $emails = $items->pluck('email')->toArray();
            $employees = Employee::whereIn('email', $emails)
                ->get()
                ->keyBy('email');

            $items->each(function (array $item) use ($property, $employees) {
                $email = $item['email'];

                if (isset($employees[$email]) && $item[$property]) {
                    $employee = $employees[$email];
                    $employee->source_id = $item[$property];
                    $employee->save();
                }
            });
        });
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $url = $this->ask('Адрес API внешнего источника');
        $property = $this->ask('Название свойства с идентификатором', 'id');

        $this->validateInput($url, $property);

        $this->info('Получение данных...');

        $response = Http::get($url);

        if ($response->successful()) {
            $items = collect($response->json());

            $this->sync($items, $property);
        }
    }
}
