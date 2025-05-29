<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LessonCancellationWeather extends Mailable
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
        return $this->markdown('emails.lesson-cancellation-weather')
                    ->subject('Les geannuleerd wegens weersomstandigheden');
    }
}
