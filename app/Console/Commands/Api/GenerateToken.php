<?php

namespace App\Console\Commands\Api;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:generate-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Генерирует токен для доступа к API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $token = Str::random(32);

        $envFile = base_path('.env');
        file_put_contents($envFile, PHP_EOL."API_TOKEN=$token".PHP_EOL, FILE_APPEND);

        $this->info('Cгенерированный токен сохранен в .env файл');
        $this->info('Новый токен: '.$token);
    }
}
