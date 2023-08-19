<?php

namespace App\Models\Statistic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Competence extends Model
{
    protected $table = 'statistic_competences';

    protected $guarded = [];

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'statistic_client_id', 'statistic_competence_id')
            ->withTimestamps();
    }
}
