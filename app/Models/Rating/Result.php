<?php

namespace App\Models\Rating;

use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Result extends Model
{
    use SoftDeletes;

    protected $table = 'rating_results';

    protected $guarded = [];

    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class, 'company_employee_id');
    }

    public function rating(): BelongsTo {
        return $this->belongsTo(Rating::class, 'rating_id')
            ->withTrashed();
    }

    public function clients(): HasMany {
        return $this->hasMany(ResultClient::class, 'rating_result_id')
            ->withTrashed();
    }
}
