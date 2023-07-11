<?php

namespace App\Mail\Rating;

use App\Models\Company\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RatingLaunchMail extends Mailable //implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Employee $employee;

    public bool $selfRating;

    /**
     * Create a new message instance.
     */
    public function __construct(Employee $employee, bool $selfRating = false)
    {
        $this->employee = $employee;
        $this->selfRating = $selfRating;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->selfRating ? 'Cамооценка' : 'Оценка сотрудника - '.$this->employee->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.rating.rating-launch',
        );
    }
}
