<?php

namespace App\Observers\Rating;

use App\Mail\Rating\RatingLaunchMail;
use App\Models\Rating\Rating;
use Illuminate\Support\Facades\Mail;

class RatingObserver
{
    public function updated(Rating $rating): void
    {
        if ( ! $rating->isDirty('status')) {
            return;
        }

        if ( ! $rating->launched_at && $rating->status === 'in progress') {
            $this->sendEmails($rating);

            $rating->launched_at = now();
            $rating->save();
        }
    }

    private function sendEmails(Rating $rating) {
        if (empty($rating->matrix->templates)) {
            return;
        }

        foreach ($rating->matrix->templates as $template) {
            Mail::to($template->employee->user->email)
                ->send(new RatingLaunchMail($template->employee, true));

            if ($template->employee->directManager) {
                Mail::to($template->employee->directManager->user->email)
                    ->send(new RatingLaunchMail($template->employee));
            }

            if ($template->employee->functionalManager) {
                Mail::to($template->employee->functionalManager->user->email)
                    ->send(new RatingLaunchMail($template->employee));
            }

            if ($template->clients->isEmpty()) {
                return;
            }

            foreach ($template->clients as $client) {
                Mail::to($client->employeeUser->email)
                    ->send(new RatingLaunchMail($template->employee));
            }
        }
    }
}
