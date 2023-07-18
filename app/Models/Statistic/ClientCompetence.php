<?php

namespace App\Models\Statistic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientCompetence extends Model
{
    protected $table = 'statistic_client_competences';

    protected $guarded = [];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'statistic_client_id');
    }

    public function competence(): BelongsTo
    {
        return $this->belongsTo(Competence::class, 'statistic_competence_id');
    }

    public function markers(): HasMany
    {
        return $this->hasMany(Marker::class, 'statistic_client_competence_id');
    }
}
