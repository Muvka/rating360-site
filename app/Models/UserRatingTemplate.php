<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRatingTemplate extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function competences(): HasMany {
        return $this->hasMany(UserRatingTemplateCompetence::class, 'user_rating_template_id');
    }
}
