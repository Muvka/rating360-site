<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Statistic\Result;

class Rating extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function template(): BelongsTo {
        return $this->belongsTo(Template::class, 'rating_template_id')
            ->withTrashed();
    }

    public function matrix(): BelongsTo {
        return $this->belongsTo(Matrix::class, 'rating_matrix_id')
            ->withTrashed();
    }

    public function matrixTemplates(): HasMany {
        return $this->hasMany(MatrixTemplate::class, 'rating_matrix_id', 'rating_matrix_id');
    }

    public function results() :HasMany {
        return $this->hasMany(Result::class);
    }
}
