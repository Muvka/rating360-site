<?php

namespace App\Models\Rating;

use App\Models\Company\Employee;
use App\Models\Shared\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Znck\Eloquent\Relations\BelongsToThrough;

class MatrixTemplateClient extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $table = 'rating_matrix_template_clients';

    protected $guarded = [];

    public function template(): BelongsTo {
        return $this->belongsTo(MatrixTemplate::class, 'rating_matrix_template_id');
    }

    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class, 'company_employee_id');
    }

    public function user(): BelongsToThrough {
        return $this->belongsToThrough(User::class, Employee::class);
    }
}
