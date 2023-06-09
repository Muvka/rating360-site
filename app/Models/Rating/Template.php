<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    protected $table = 'rating_templates';

    protected $guarded = [];

    public function competences(): HasMany {
        return $this->hasMany(TemplateCompetence::class, 'rating_template_id');
    }
}
