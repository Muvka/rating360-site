<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatrixTemplate extends Model
{
    protected $table = 'rating_matrix_templates';

    protected $guarded = [];

    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class, 'rating_employee_id');
    }

    public function matrix(): BelongsTo {
        return $this->belongsTo(Matrix::class, 'rating_matrix_id');
    }

    public function clients(): HasMany {
        return $this->hasMany(MatrixTemplateClient::class, 'rating_matrix_template_id');
    }
}
