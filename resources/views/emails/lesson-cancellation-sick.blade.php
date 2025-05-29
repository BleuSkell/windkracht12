@component('mail::message')
# Les Geannuleerd - Instructeur Ziek

Beste {{ $reservation->user->contact->firstName }},

Je les van {{ \Carbon\Carbon::parse($reservation->reservationDate)->format('d-m-Y') }} 
om {{ \Carbon\Carbon::parse($reservation->reservationTime)->format('H:i') }} uur is geannuleerd 
omdat je instructeur ziek is.

**Details:**
- Locatie: {{ $reservation->location->name }}
- Instructeur: {{ $instructor->user->contact->firstName }} {{ $instructor->user->contact->lastName }}

We nemen binnenkort contact met je op om een nieuwe afspraak in te plannen.

Met vriendelijke groet,
Team Windkracht-12
@endcomponent
