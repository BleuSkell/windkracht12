<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Instructor;
use App\Models\Reservation;
use App\Models\Package;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\LessonCancellationWeather;
use App\Mail\LessonCancellationSick;
use App\Mail\LessonCancellationApproved;
use App\Mail\LessonCancellationRejected;
use Carbon\Carbon;

class InstructorController extends Controller
{
    public function customerIndex()
    {
        $instructor = auth()->user()->instructor;
        $customers = $instructor->customers()
            ->with(['user', 'user.contact', 'user.roles'])
            ->get();

        return view('instructors.customers.index', compact('customers'));
    }

    public function customerCreate()
    {
        // Get customers that don't have any instructors
        $availableCustomers = Customer::doesntHave('instructors')
            ->with(['user.contact'])
            ->get();

        return view('instructors.customers.create', compact('availableCustomers'));
    }

    public function customerStore(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id'
        ]);

        try {
            $instructor = auth()->user()->instructor;
            $instructor->customers()->attach($validated['customer_id']);

            return redirect()->route('instructor.customers.index')
                ->with('success', 'Klant succesvol gekoppeld.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Er is iets misgegaan: ' . $e->getMessage()]);
        }
    }

    public function customerEdit(Customer $customer)
    {
        $instructor = auth()->user()->instructor;
        if (!$instructor->customers->contains($customer->id)) {
            return redirect()->route('instructor.customers.index')
                ->with('error', 'Je hebt geen toegang tot deze klant.');
        }

        $customer->load('user.contact');
        return view('instructors.customers.edit', compact('customer'));
    }

    public function customerUpdate(Request $request, Customer $customer)
    {
        $instructor = auth()->user()->instructor;
        if (!$instructor->customers->contains($customer->id)) {
            return redirect()->route('instructor.customers.index')
                ->with('error', 'Je hebt geen toegang tot deze klant.');
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,'.$customer->user->id,
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'adress' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'dateOfBirth' => 'required|date',
            'mobile' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($customer, $validated) {
                $customer->user->update(['email' => $validated['email']]);
                
                $customer->user->contact->update([
                    'firstName' => $validated['firstName'],
                    'lastName' => $validated['lastName'],
                    'adress' => $validated['adress'],
                    'city' => $validated['city'],
                    'dateOfBirth' => $validated['dateOfBirth'],
                    'mobile' => $validated['mobile'],
                ]);
            });

            return redirect()->route('instructor.customers.index')
                ->with('success', 'Klantgegevens succesvol bijgewerkt.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Er is iets misgegaan: ' . $e->getMessage()]);
        }
    }

    public function customerLessons(Customer $customer)
    {
        $instructor = auth()->user()->instructor;
        if (!$instructor->customers->contains($customer->id)) {
            return redirect()->route('instructor.customers.index')
                ->with('error', 'Je hebt geen toegang tot deze klant.');
        }

        $lessons = Reservation::where('userId', $customer->userId)
            ->with(['package', 'location'])
            ->orderBy('reservationDate', 'desc')
            ->get();

        return view('instructors.customers.lessons', compact('customer', 'lessons'));
    }

    public function updateCustomerLessons(Request $request, Customer $customer)
    {
        $instructor = auth()->user()->instructor;
        if (!$instructor->customers->contains($customer->id)) {
            return redirect()->route('instructor.customers.index')
                ->with('error', 'Je hebt geen toegang tot deze klant.');
        }

        $validated = $request->validate([
            'lesson_id' => 'required|exists:reservations,id',
            'status' => 'required|in:completed,cancelled,pending',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $lesson = Reservation::findOrFail($validated['lesson_id']);
            $lesson->update([
                'status' => $validated['status'],
                'notes' => $validated['notes'],
            ]);

            return back()->with('success', 'Lesgegevens zijn bijgewerkt.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Er is iets misgegaan: ' . $e->getMessage()]);
        }
    }

    public function customerDestroy(Customer $customer)
    {
        // Check if customer belongs to instructor
        $instructor = auth()->user()->instructor;
        if (!$instructor->customers->contains($customer->id)) {
            return redirect()->route('instructors.customers.index')
                ->with('error', 'Je hebt geen toegang tot deze klant.');
        }

        try {
            // Only detach the customer from this instructor
            $instructor->customers()->detach($customer->id);
            
            return redirect()->route('instructors.customers.index')
                ->with('success', 'Klant succesvol ontkoppeld van jouw klantenlijst.');
        } catch (\Exception $e) {
            return back()->with('error', 'Er is iets misgegaan: ' . $e->getMessage());
        }
    }

    public function reservationEdit(Customer $customer, Reservation $reservation)
    {
        $instructor = auth()->user()->instructor;
        if (!$instructor->customers->contains($customer->id)) {
            return redirect()->route('instructor.customers.index')
                ->with('error', 'Je hebt geen toegang tot deze klant.');
        }

        $packages = Package::all();
        $locations = Location::all();

        return view('instructors.customers.reservations.edit', compact('customer', 'reservation', 'packages', 'locations'));
    }

    public function reservationUpdate(Request $request, Customer $customer, Reservation $reservation)
    {
        $instructor = auth()->user()->instructor;
        if (!$instructor->customers->contains($customer->id)) {
            return redirect()->route('instructor.customers.index')
                ->with('error', 'Je hebt geen toegang tot deze klant.');
        }

        $updateData = [];
        
        if ($request->has('packageId')) {
            $updateData['packageId'] = $request->packageId;
        }
        if ($request->has('locationId')) {
            $updateData['locationId'] = $request->locationId;
        }
        if ($request->has('reservationDate')) {
            $updateData['reservationDate'] = $request->reservationDate;
        }
        if ($request->has('reservationTime')) {
            $updateData['reservationTime'] = $request->reservationTime;
        }

        try {
            $reservation->update($updateData);

            return redirect()->route('instructor.customers.lessons', $customer)
                ->with('success', 'Reservering succesvol bijgewerkt.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Er is iets misgegaan: ' . $e->getMessage()]);
        }
    }

    public function cancelLessonWeather(Customer $customer, Reservation $reservation)
    {
        $instructor = auth()->user()->instructor;
        if (!$instructor->customers->contains($customer->id)) {
            return redirect()->route('instructor.customers.index')
                ->with('error', 'Je hebt geen toegang tot deze klant.');
        }

        try {
            DB::transaction(function () use ($reservation, $instructor, $customer) {
                // Send email before deleting
                Mail::to($customer->user->email)
                    ->send(new LessonCancellationWeather($reservation, $instructor));
                
                // Also send to instructor
                Mail::to($instructor->user->email)
                    ->send(new LessonCancellationWeather($reservation, $instructor));

                // Delete any related records first (if needed)
                if ($reservation->invoice) {
                    $reservation->invoice->delete();
                }

                // Finally delete the reservation
                $reservation->delete();
            });

            return back()->with('success', 'Les geannuleerd en e-mails verzonden.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Er is iets misgegaan: ' . $e->getMessage()]);
        }
    }

    public function cancelLessonSick(Customer $customer, Reservation $reservation)
    {
        $instructor = auth()->user()->instructor;
        if (!$instructor->customers->contains($customer->id)) {
            return redirect()->route('instructor.customers.index')
                ->with('error', 'Je hebt geen toegang tot deze klant.');
        }

        try {
            DB::transaction(function () use ($reservation, $instructor, $customer) {
                // Send email before deleting
                Mail::to($customer->user->email)
                    ->send(new LessonCancellationSick($reservation, $instructor));
                
                // Also send to instructor
                Mail::to($instructor->user->email)
                    ->send(new LessonCancellationSick($reservation, $instructor));

                // Delete any related records first (if needed)
                if ($reservation->invoice) {
                    $reservation->invoice->delete();
                }

                // Finally delete the reservation
                $reservation->delete();
            });

            return back()->with('success', 'Les geannuleerd en e-mails verzonden.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Er is iets misgegaan: ' . $e->getMessage()]);
        }
    }

    public function scheduleDay()
    {
        $instructor = auth()->user()->instructor;
        $date = request('date', now()->toDateString());
        
        $lessons = Reservation::whereIn('userId', $instructor->customers->pluck('userId'))
            ->whereDate('reservationDate', $date)
            ->with(['package', 'location', 'user.contact'])
            ->orderBy('reservationTime')
            ->get();

        return view('instructors.schedule.day', compact('lessons', 'date'));
    }

    public function scheduleWeek()
    {
        $instructor = auth()->user()->instructor;
        $startDate = request('date', now()->startOfWeek()->toDateString());
        $endDate = Carbon::parse($startDate)->endOfWeek()->toDateString();
        
        $lessons = Reservation::whereIn('userId', $instructor->customers->pluck('userId'))
            ->whereBetween('reservationDate', [$startDate, $endDate])
            ->with(['package', 'location', 'user.contact'])
            ->orderBy('reservationDate')
            ->orderBy('reservationTime')
            ->get()
            ->groupBy('reservationDate');

        return view('instructors.schedule.week', compact('lessons', 'startDate', 'endDate'));
    }

    public function scheduleMonth()
    {
        $instructor = auth()->user()->instructor;
        $date = request('date', now()->startOfMonth()->toDateString());
        $startDate = Carbon::parse($date)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        $lessons = Reservation::whereIn('userId', $instructor->customers->pluck('userId'))
            ->whereBetween('reservationDate', [$startDate, $endDate])
            ->with(['package', 'location', 'user.contact'])
            ->orderBy('reservationDate')
            ->orderBy('reservationTime')
            ->get()
            ->groupBy('reservationDate');

        return view('instructors.schedule.month', compact('lessons', 'startDate', 'endDate'));
    }

    public function approveCancellation(Customer $customer, Reservation $reservation)
    {
        $instructor = auth()->user()->instructor;
        if (!$instructor->customers->contains($customer->id)) {
            return redirect()->route('instructor.customers.index')
                ->with('error', 'Je hebt geen toegang tot deze klant.');
        }

        try {
            DB::transaction(function () use ($reservation, $instructor, $customer) {
                // Send confirmation emails
                Mail::to($customer->user->email)
                    ->send(new LessonCancellationApproved($reservation));
                
                Mail::to($instructor->user->email)
                    ->send(new LessonCancellationApproved($reservation));

                // Delete related invoice if exists
                if ($reservation->invoice) {
                    $reservation->invoice->delete();
                }

                // Delete the reservation
                $reservation->delete();
            });

            return back()->with('success', 'Annulering goedgekeurd en e-mails verzonden.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Er is iets misgegaan: ' . $e->getMessage()]);
        }
    }

    public function rejectCancellation(Customer $customer, Reservation $reservation)
    {
        $instructor = auth()->user()->instructor;
        if (!$instructor->customers->contains($customer->id)) {
            return redirect()->route('instructor.customers.index')
                ->with('error', 'Je hebt geen toegang tot deze klant.');
        }

        try {
            DB::transaction(function () use ($reservation, $customer) {
                // Reset cancellation fields
                $reservation->update([
                    'cancellationStatus' => 'rejected',
                    'cancellationReason' => null,
                    'originalDate' => null,
                    'originalTime' => null,
                ]);

                // Send rejection email
                Mail::to($customer->user->email)
                    ->send(new LessonCancellationRejected($reservation));
            });

            return back()->with('success', 'Annulering afgewezen en e-mail verzonden.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Er is iets misgegaan: ' . $e->getMessage()]);
        }
    }

    public function cancellationRequests()
    {
        $instructor = auth()->user()->instructor;
        
        $requests = Reservation::whereIn('userId', $instructor->customers->pluck('userId'))
            ->where('cancellationStatus', 'pending')
            ->with(['user.contact', 'package', 'location'])
            ->orderBy('reservationDate')
            ->get();

        return view('instructors.cancellation-requests', compact('requests'));
    }
}