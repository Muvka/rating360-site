<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    protected $guarded = [];

    public function template(): BelongsTo {
        return $this->belongsTo(Template::class, 'rating_template_id');
    }

    public function matrix(): BelongsTo {
        return $this->belongsTo(Matrix::class, 'rating_matrix_id');
    }
}
