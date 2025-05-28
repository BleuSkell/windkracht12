<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Location;
use App\Models\Date;
use App\Models\Reservation;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmation;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::where('userId', auth()->id())
            ->with(['package', 'location', 'invoice'])
            ->get();

        // dd($reservations[0]->invoice);
            
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $packages = Package::all();
        $locations = Location::all();
        
        return view('reservations.create', compact('packages', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'packageId' => 'required|exists:packages,id',
            'locationId' => 'required|exists:locations,id',
            'dateId' => 'required|exists:dates,id',
            'duoPartnerName' => 'required_if:isDuo,true',
            'duoPartnerEmail' => 'required_if:isDuo,true|email',
            'duoPartnerAddress' => 'required_if:isDuo,true',
            'duoPartnerCity' => 'required_if:isDuo,true',
            'duoPartnerPhone' => 'required_if:isDuo,true',
            'reservationDate' => 'required|date',
            'reservationTime' => 'required|date_format:H:i',
        ]);

        // Check if reservationTime is between 10:00 and 20:00
        $reservationTime = $request->reservationTime;
        if ($reservationTime < '10:00' || $reservationTime > '20:00') {
            return back()
                ->withInput()
                ->withErrors(['reservationTime' => 'De reserveringstijd moet tussen 10:00 en 20:00 liggen.']);
        }

        $package = Package::findOrFail($request->packageId);

        $reservation = Reservation::create([
            'userId' => auth()->id(),
            'packageId' => $request->packageId,
            'locationId' => $request->locationId,
            'dateId' => $request->dateId,
            'duoPartnerName' => $request->duoPartnerName,
            'duoPartnerEmail' => $request->duoPartnerEmail,
            'duoPartnerAddress' => $request->duoPartnerAddress,
            'duoPartnerCity' => $request->duoPartnerCity,
            'duoPartnerPhone' => $request->duoPartnerPhone,
            'reservationDate' => $request->reservationDate,
            'reservationTime' => $request->reservationTime,
        ]);

        $invoice = Invoice::create([
            'reservationId' => $reservation->id,
            'invoiceNumber' => 'INV-' . date('Y') . '-' . str_pad($reservation->id, 5, '0', STR_PAD_LEFT),
            'amount' => $package->price,
            'dueDate' => now()->addDays(14)
        ]);

        Mail::to(auth()->user()->email)
            ->send(new ReservationConfirmation($reservation));

        if ($reservation->duoPartnerEmail) {
            Mail::to($reservation->duoPartnerEmail)
                ->send(new ReservationConfirmation($reservation));
        }

        return redirect()->route('reservations.index')
            ->with('success', 'Reservering succesvol aangemaakt! Check je email voor de factuur.');
    }

    public function updatePaymentStatus(Reservation $reservation)
    {
        $reservation->invoice->update(['status' => 'paid']);
        
        return redirect()->route('reservations.index')
            ->with('success', 'Betalingsstatus bijgewerkt.');
    }
}