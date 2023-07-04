<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    protected $table = 'rating_templates';

    protected $guarded = [];

    public function ratings(): HasMany {
        return $this->hasMany(Rating::class, 'rating_template_id');
    }

    public function markers(): HasMany {
        return $this->hasMany(TemplateMarker::class, 'rating_template_id');
    }
}
