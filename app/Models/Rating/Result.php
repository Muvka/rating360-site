<?php

namespace App\Models\Rating;

use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function directions(): BelongsToMany {
        return $this->belongsToMany(Direction::class, 'rating_direction_result', 'rating_result_id', 'rating_direction_id')
            ->withTimestamps();
    }

    public function clients(): HasMany {
        return $this->hasMany(ResultClient::class, 'rating_result_id');
    }
}
