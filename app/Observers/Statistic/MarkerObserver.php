<?php

namespace App\Observers\Statistic;

use App\Models\Statistic\Marker;

class MarkerObserver
{
    public function created(Marker $marker): void
    {
        $this->calculateAverageRating($marker);
    }

    public function updated(Marker $marker): void
    {
        if ($marker->isDirty('rating')) {
            $this->calculateAverageRating($marker);
        }
    }

    public function deleted(Marker $marker): void
    {
        $this->calculateAverageRating($marker);
    }

    public function restored(Marker $marker): void
    {
        $this->calculateAverageRating($marker);
    }

    private function calculateAverageRating(Marker $marker): void
    {
        $clientCompetence = $marker->clientCompetence;
        $clientCompetence->average_rating = $marker->clientCompetence->markers->avg('rating');
        $clientCompetence->save();
    }
}
