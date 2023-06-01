<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRatingMatrixTemplate extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function employee(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function matrix(): BelongsTo {
        return $this->belongsTo(UserRatingMatrix::class, 'user_rating_matrix_id');
    }

    public function directions(): HasMany {
        return $this->hasMany(UserRatingMatrixTemplateDirection::class, 'user_rating_matrix_template_id');
    }
}
