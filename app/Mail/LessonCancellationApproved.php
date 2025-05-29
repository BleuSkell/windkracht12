<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LessonCancellationApproved extends Mailable
{
    use SerializesModels;

    public $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->markdown('emails.lesson-cancellation-approved')
                    ->subject('Annulering les goedgekeurd');
    }
}
