@component('mail::message')
# Kitesurfles Definitief Bevestigd

Beste {{ $reservation->user->contact->firstName }},

Je kitesurfles is nu definitief bevestigd. Hieronder vind je de details:

**Lesgegevens:**
- Datum: {{ \Carbon\Carbon::parse($reservation->reservationDate)->format('d-m-Y') }}
- Tijd: {{ \Carbon\Carbon::parse($reservation->reservationTime)->format('H:i') }}
- Locatie: {{ $reservation->location->name }}
- Pakket: {{ $reservation->package->name }}

@if($reservation->package->isDuo)
**Duo Partner:**
- Naam: {{ $reservation->duoPartnerName }}
- Email: {{ $reservation->duoPartnerEmail }}
@endif

Zorg dat je 15 minuten voor aanvang aanwezig bent.

Met vriendelijke groet,<br>
{{ config('app.name') }}
@endcomponent
