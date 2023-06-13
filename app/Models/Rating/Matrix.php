<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matrix extends Model
{
    use SoftDeletes;

    protected $table = 'rating_matrices';

    protected $guarded = [];

    public function ratings(): HasMany {
        return $this->hasMany(Rating::class, 'rating_matrix_id');
    }

    public function templates(): HasMany {
        return $this->hasMany(MatrixTemplate::class, 'rating_matrix_id');
    }
}
