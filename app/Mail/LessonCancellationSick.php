<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LessonCancellationSick extends Mailable
{
    use SerializesModels;

    public $reservation;
    public $instructor;

    public function __construct($reservation, $instructor)
    {
        $this->reservation = $reservation;
        $this->instructor = $instructor;
    }

    public function build()
    {
        return $this->markdown('emails.lesson-cancellation-sick')
                    ->subject('Les geannuleerd wegens ziekte instructeur');
    }
}
