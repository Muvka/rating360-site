<?php

namespace App\Observers\Rating;

use App\Models\Rating\Rating;
use App\Services\Rating\AssessmentStartNotifyService;

class RatingObserver
{
    public function updated(Rating $rating): void
    {
        if (! $rating->isDirty('status')) {
            return;
        }

        if (! $rating->launched_at && $rating->status === 'in progress') {
            (new AssessmentStartNotifyService())->notify($rating);

            $rating->launched_at = now();
            $rating->save();
        }
    }
}
