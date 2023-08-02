<?php

namespace App\Console\Commands\Rating;

use App\Models\Rating\Rating;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class Delete extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rating:delete {rating : ID оценки} {--force : Удалить полностью}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Удаление оценки';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $rating = Rating::findOrFail($this->argument('rating'));

        if ($this->confirm('Хотите ли вы продолжить?')) {
            if ($this->option('force')) {
                $rating->forceDelete();
            } else {
                $rating->delete();
            }
        }
    }

    protected function promptForMissingArgumentsUsing()
    {
        return [
            'rating' => 'Укажите id оценки, которую нужно удалить',
        ];
    }
}
