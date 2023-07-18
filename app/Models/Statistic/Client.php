<?php

namespace App\Models\Statistic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $table = 'statistic_clients';

    protected $guarded = [];

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class, 'statistic_result_id');
    }

    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'statistic_competence_id', 'statistic_client_id')
            ->withTimestamps();
    }

    public function clientCompetences(): HasMany
    {
        return $this->hasMany(ClientCompetence::class, 'statistic_client_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'statistic_client_id');
    }
}
