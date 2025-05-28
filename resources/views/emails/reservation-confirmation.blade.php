<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Reservering Bevestiging</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f7f7f7;
            color: #222;
            margin: 0;
            padding: 0;
        }
        .container {
            background: #fff;
            max-width: 600px;
            margin: 30px auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 32px 28px 28px 28px;
        }
        h1 {
            color: #1a6fa3;
            font-size: 2em;
            margin-bottom: 0.5em;
        }
        h2 {
            color: #1a6fa3;
            font-size: 1.2em;
            margin-top: 2em;
            margin-bottom: 0.5em;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 0.2em;
        }
        p {
            margin: 0.5em 0 1em 0;
            line-height: 1.5;
        }
        strong {
            color: #333;
        }
        .iban-box {
            background: #f0f6fa;
            border-left: 4px solid #1a6fa3;
            padding: 12px 18px;
            margin: 1.5em 0;
            border-radius: 4px;
        }
        .footer {
            margin-top: 2em;
            font-size: 0.95em;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Reservering bevestiging</h1>

    <p>Bedankt voor je reservering bij <strong>Windkracht-12</strong>. Hieronder vind je de details van je reservering:</p>

    <h2>Reserveringsgegevens</h2>
    <p><strong>Pakket:</strong> {{ $reservation->package->name }}</p>
    <p><strong>Locatie:</strong> {{ $reservation->location->name }}</p>
    <p><strong>Datum:</strong> {{ \Carbon\Carbon::parse($reservation->reservationDate)->format('d-m-Y') }}</p>
    <p><strong>Tijd:</strong> {{ \Carbon\Carbon::parse($reservation->reservationTime)->format('H:i') }}</p>

    @if($reservation->package->isDuo)
        <h2>Duo Partner Gegevens</h2>
        <p><strong>Naam:</strong> {{ $reservation->duoPartnerName }}</p>
        <p><strong>Email:</strong> {{ $reservation->duoPartnerEmail }}</p>
        <p><strong>Adres:</strong> {{ $reservation->duoPartnerAddress }}</p>
        <p><strong>Stad:</strong> {{ $reservation->duoPartnerCity }}</p>
        <p><strong>Telefoon:</strong> {{ $reservation->duoPartnerPhone }}</p>
    @endif

    <h2>Factuurgegevens</h2>
    <p><strong>Factuurnummer:</strong> {{ $reservation->invoice->invoiceNumber }}</p>
    <p><strong>Bedrag:</strong> €{{ number_format($reservation->invoice->amount, 2, ',', '.') }}</p>
    <p><strong>Vervaldatum:</strong> {{ \Carbon\Carbon::parse($reservation->invoice->dueDate)->format('d-m-Y') }}</p>

    <div class="iban-box">
        <p>Graag het bedrag overmaken naar:</p>
        <p><strong>IBAN:</strong> NL12 RABO 0123 4567 89</p>
        <p><strong>T.n.v.:</strong> Windkracht-12</p>
        <p><strong>O.v.v.:</strong> {{ $reservation->invoice->invoiceNumber }}</p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Windkracht-12. Alle rechten voorbehouden.
    </div>
</div>
</body>
</html>