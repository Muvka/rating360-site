<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matrix extends Model
{
    protected $table = 'rating_matrices';

    protected $guarded = [];

    public function templates(): HasMany {
        return $this->hasMany(MatrixTemplate::class, 'rating_matrix_id');
    }
}
