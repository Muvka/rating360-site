<?php

namespace App\Observers\Rating;

use App\Models\Rating\Competence as RatingCompetence;
use App\Models\Statistic\Competence as StatisticCompetence;

class CompetenceObserver
{
    public function saved(RatingCompetence $competence): void
    {
        if ($competence->isDirty('description')) {
            StatisticCompetence::query()
                ->where('name', $competence->name)
                ->update(['description' => $competence->description]);
        }
    }
}
