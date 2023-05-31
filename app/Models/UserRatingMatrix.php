<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRatingMatrix extends Model
{
    protected $guarded = [];

    public function templates(): HasMany {
        return $this->hasMany(UserRatingMatrixTemplate::class, 'user_rating_matrix_id');
    }
}
