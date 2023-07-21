<?php

namespace App\Models\Rating;

use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatrixTemplate extends Model
{
    protected $table = 'rating_matrix_templates';

    protected $guarded = [];

    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class, 'company_employee_id');
    }

    public function matrix(): BelongsTo {
        return $this->belongsTo(Matrix::class, 'rating_matrix_id');
    }

    public function clients(): HasMany {
        return $this->hasMany(MatrixTemplateClient::class, 'rating_matrix_template_id');
    }

    public function clientsWithoutSelf(): HasMany {
        return $this->hasMany(MatrixTemplateClient::class, 'rating_matrix_template_id')
            ->whereNot('type', 'self');
    }

    public function editableClients(): HasMany {
        return $this->hasMany(MatrixTemplateClient::class, 'rating_matrix_template_id')
            ->whereIn('type', ['inner', 'outer']);
    }

    public function innerClients(): HasMany {
        return $this->hasMany(MatrixTemplateClient::class, 'rating_matrix_template_id')
            ->where('type', 'inner');
    }

    public function outerClients(): HasMany {
        return $this->hasMany(MatrixTemplateClient::class, 'rating_matrix_template_id')
            ->where('type', 'outer');
    }
}
