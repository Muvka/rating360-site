<?php

namespace Database\Factories\Shared;

use App\Models\Shared\Faq;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    protected $model = Faq::class;

    /**
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'question' => fake()->realTextBetween(30, 100),
            'answer' => fake()->realTextBetween(200, 1000),
            'is_published' => fake()->boolean(80),
            'sort' => fake()->numberBetween(0, 1000),
        ];
    }
}
