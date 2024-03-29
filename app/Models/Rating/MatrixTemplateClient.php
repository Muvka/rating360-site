<?php

namespace App\Models\Rating;

use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatrixTemplateClient extends Model
{
    protected $table = 'rating_matrix_template_clients';

    protected $guarded = [];

    public function template(): BelongsTo
    {
        return $this->belongsTo(MatrixTemplate::class, 'rating_matrix_template_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'company_employee_id');
    }
}
