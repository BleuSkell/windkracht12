@component('mail::message')
# Annulering Afgewezen

Beste {{ $reservation->user->contact->firstName }},

Je annuleringsverzoek voor de les op {{ \Carbon\Carbon::parse($reservation->reservationDate)->format('d-m-Y') }} 
om {{ \Carbon\Carbon::parse($reservation->reservationTime)->format('H:i') }} uur is afgewezen.

De oorspronkelijke afspraak blijft staan. Als je vragen hebt, neem dan contact met ons op.

Met vriendelijke groet,
Team Windkracht-12
@endcomponent
