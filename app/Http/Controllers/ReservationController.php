<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Location;
use App\Models\Date;
use App\Models\Reservation;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmation;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::where('userId', auth()->id())
            ->with(['package', 'location', 'invoice'])
            ->orderBy('created_at', 'desc')
            ->get();
            
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
        try {
            // Validate the request
            $validated = $request->validate([
                'packageId' => 'required|exists:packages,id',
                'locationId' => 'required|exists:locations,id',
                'reservationDate' => 'required|date|after_or_equal:today',
                'reservationTime' => 'required|date_format:H:i',
                'duoPartnerName' => 'nullable|required_if:isDuo,true',
                'duoPartnerEmail' => 'nullable|required_if:isDuo,true|email',
                'duoPartnerAddress' => 'nullable|required_if:isDuo,true',
                'duoPartnerCity' => 'nullable|required_if:isDuo,true',
                'duoPartnerPhone' => 'nullable|required_if:isDuo,true',
            ]);

            DB::beginTransaction();

            $package = Package::findOrFail($request->packageId);

            // Create the reservation
            $reservation = Reservation::create([
                'userId' => auth()->id(),
                'packageId' => $request->packageId,
                'locationId' => $request->locationId,
                'reservationDate' => $request->reservationDate,
                'reservationTime' => $request->reservationTime,
                'duoPartnerName' => $request->duoPartnerName,
                'duoPartnerEmail' => $request->duoPartnerEmail,
                'duoPartnerAddress' => $request->duoPartnerAddress,
                'duoPartnerCity' => $request->duoPartnerCity,
                'duoPartnerPhone' => $request->duoPartnerPhone,
            ]);

            // Create the invoice
            $invoice = Invoice::create([
                'reservationId' => $reservation->id,
                'invoiceNumber' => 'INV-' . date('Y') . '-' . str_pad($reservation->id, 5, '0', STR_PAD_LEFT),
                'amount' => $package->price,
                'status' => 'unpaid',
                'dueDate' => now()->addDays(14)
            ]);

            DB::commit();

            // Send confirmation emails
            Mail::to(auth()->user()->email)
                ->send(new ReservationConfirmation($reservation));

            if ($reservation->duoPartnerEmail) {
                Mail::to($reservation->duoPartnerEmail)
                    ->send(new ReservationConfirmation($reservation));
            }

            return redirect()->route('reservations.index')
                ->with('success', 'Reservering succesvol aangemaakt! Check je email voor de factuur.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Er is iets misgegaan bij het maken van de reservering: ' . $e->getMessage()]);
        }
    }

    public function updatePaymentStatus(Reservation $reservation)
    {
        $reservation->invoice->update(['status' => 'paid']);
        
        return redirect()->route('reservations.index')
            ->with('success', 'Betalingsstatus bijgewerkt.');
    }
}