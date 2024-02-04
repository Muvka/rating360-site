<?php

namespace App\Console\Commands\Application;

use Illuminate\Console\Command;

class Install extends Command
{
    protected $signature = 'application:install';

    protected $description = 'Установка приложения';

    public function handle(): int
    {
        $this->call('key:generate');
        $this->call('storage:link');
        $this->call('migrate');
        $this->call('config:cache');
        $this->call('route:cache');

        return self::SUCCESS;
    }
}
