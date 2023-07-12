<?php

namespace App\Casts\Rating;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ResultRating implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_string($value)) {
            $transform = [
                'always' => 5,
                'often' => 4,
                'sometimes' => 3,
                'rarely' => 2,
                'never' => 1,
            ];

            return $transform[$value] ?? null;
        } else {
            return $value;
        }
    }
}
