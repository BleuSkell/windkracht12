@component('mail::message')
# Annulering Goedgekeurd

Beste {{ $reservation->user->contact->firstName }},

Je annuleringsverzoek voor de les op {{ \Carbon\Carbon::parse($reservation->reservationDate)->format('d-m-Y') }} 
om {{ \Carbon\Carbon::parse($reservation->reservationTime)->format('H:i') }} uur is goedgekeurd.

Je kunt nu een nieuwe datum inplannen via je account op onze website.

Met vriendelijke groet,
Team Windkracht-12
@endcomponent
