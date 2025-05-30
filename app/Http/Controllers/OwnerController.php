<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Customer;
use App\Models\Instructor;
use App\Models\Reservation;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use App\Mail\LessonCancellationWeather;
use App\Mail\LessonCancellationSick;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OwnerController extends Controller
{
    public function customerIndex()
    {
        $customers = Customer::with(['user.contact', 'user.roles', 'instructors.user.contact'])->get();
        return view('owner.customers.index', compact('customers'));
    }

    public function customerCreate()
    {
        $instructors = Instructor::with('user.contact')->get();
        return view('owner.customers.create', compact('instructors'));
    }

    public function customerStore(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'adress' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'dateOfBirth' => 'required|date',
            'mobile' => 'required|string|max:255',
            'instructors' => 'required|array|min:1',
            'instructors.*' => 'exists:instructors,id'
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $customerRole = Role::where('roleName', 'customer')->first();
                
                $user = User::create([
                    'email' => $validated['email'],
                    'roleId' => $customerRole->id,
                    'password' => ''
                ]);

                $user->contact()->create([
                    'firstName' => $validated['firstName'],
                    'lastName' => $validated['lastName'],
                    'adress' => $validated['adress'],
                    'city' => $validated['city'],
                    'dateOfBirth' => $validated['dateOfBirth'],
                    'mobile' => $validated['mobile']
                ]);

                $customer = $user->customer()->create();
                $customer->instructors()->attach($validated['instructors']);

                event(new Registered($user));
            });

            return redirect()->route('owner.customers.index')
                ->with('success', 'Klant succesvol aangemaakt.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Er is iets misgegaan: ' . $e->getMessage()]);
        }
    }

    public function customerEdit(Customer $customer)
    {
        $customer->load('user.contact', 'instructors');
        $instructors = Instructor::with('user.contact')->get();
        return view('owner.customers.edit', compact('customer', 'instructors'));
    }

    public function customerUpdate(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,'.$customer->user->id,
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'adress' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'dateOfBirth' => 'required|date',
            'mobile' => 'required|string|max:255',
            'instructors' => 'required|array|min:1',
            'instructors.*' => 'exists:instructors,id'
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
                    'mobile' => $validated['mobile']
                ]);

                $customer->instructors()->sync($validated['instructors']);
            });

            return redirect()->route('owner.customers.index')
                ->with('success', 'Klantgegevens succesvol bijgewerkt.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Er is iets misgegaan: ' . $e->getMessage()]);
        }
    }

    public function customerDestroy(Customer $customer)
    {
        try {
            DB::transaction(function () use ($customer) {
                // Delete related records first
                $customer->instructors()->detach();
                $customer->user->contact()->delete();
                $customer->user->delete(); // This will cascade delete the customer record
            });

            return redirect()->route('owner.customers.index')
                ->with('success', 'Klant succesvol verwijderd.');
        } catch (\Exception $e) {
            return back()->with('error', 'Er is iets misgegaan: ' . $e->getMessage());
        }
    }

    public function instructorIndex()
    {
        $instructors = Instructor::with(['user.contact', 'customers.user.contact'])->get();
        return view('owner.instructors.index', compact('instructors'));
    }

    public function instructorCreate()
    {
        return view('owner.instructors.create');
    }

    public function instructorStore(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'adress' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'dateOfBirth' => 'required|date',
            'mobile' => 'required|string|max:255',
            'bsnNumber' => 'required|string|max:255|unique:contacts,bsnNumber'
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $instructorRole = Role::where('roleName', 'instructor')->first();
                
                $user = User::create([
                    'email' => $validated['email'],
                    'roleId' => $instructorRole->id,
                    'password' => ''
                ]);

                $user->contact()->create([
                    'firstName' => $validated['firstName'],
                    'lastName' => $validated['lastName'],
                    'adress' => $validated['adress'],
                    'city' => $validated['city'],
                    'dateOfBirth' => $validated['dateOfBirth'],
                    'mobile' => $validated['mobile'],
                    'bsnNumber' => $validated['bsnNumber']
                ]);

                $user->instructor()->create();

                event(new Registered($user));
            });

            return redirect()->route('owner.instructors.index')
                ->with('success', 'Instructeur succesvol aangemaakt.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Er is iets misgegaan: ' . $e->getMessage()]);
        }
    }

    public function instructorEdit(Instructor $instructor)
    {
        $instructor->load('user.contact');
        return view('owner.instructors.edit', compact('instructor'));
    }

    public function instructorUpdate(Request $request, Instructor $instructor)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,'.$instructor->user->id,
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'adress' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'dateOfBirth' => 'required|date',
            'mobile' => 'required|string|max:255',
            'bsnNumber' => 'required|string|max:255|unique:contacts,bsnNumber,'.$instructor->user->contact->id
        ]);

        try {
            DB::transaction(function () use ($instructor, $validated) {
                $instructor->user->update(['email' => $validated['email']]);
                
                $instructor->user->contact->update([
                    'firstName' => $validated['firstName'],
                    'lastName' => $validated['lastName'],
                    'adress' => $validated['adress'],
                    'city' => $validated['city'],
                    'dateOfBirth' => $validated['dateOfBirth'],
                    'mobile' => $validated['mobile'],
                    'bsnNumber' => $validated['bsnNumber']
                ]);
            });

            return redirect()->route('owner.instructors.index')
                ->with('success', 'Instructeurgegevens succesvol bijgewerkt.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Er is iets misgegaan: ' . $e->getMessage()]);
        }
    }

    public function instructorDestroy(Instructor $instructor)
    {
        try {
            DB::transaction(function () use ($instructor) {
                // Remove instructor-customer relationships
                $instructor->customers()->detach();
                
                // Delete related records
                $instructor->user->contact()->delete();
                $instructor->user->delete(); // This will cascade delete the instructor record
            });

            return redirect()->route('owner.instructors.index')
                ->with('success', 'Instructeur succesvol verwijderd.');
        } catch (\Exception $e) {
            return back()->with('error', 'Er is iets misgegaan: ' . $e->getMessage());
        }
    }

    public function cancelLessonWeather(Customer $customer, Reservation $reservation)
    {
        try {
            DB::transaction(function () use ($reservation, $customer) {
                // Get the instructor associated with this customer and reservation
                $instructor = $customer->instructors->first();
                
                if ($instructor) {
                    // Send email to customer
                    Mail::to($customer->user->email)
                        ->send(new LessonCancellationWeather($reservation, $instructor));
                    
                    // Send email to instructor
                    Mail::to($instructor->user->email)
                        ->send(new LessonCancellationWeather($reservation, $instructor));
                }

                // Delete invoice if it exists
                if ($reservation->invoice) {
                    $reservation->invoice->delete();
                }

                // Delete the reservation
                $reservation->delete();
            });

            return back()->with('success', 'Les geannuleerd en e-mails verzonden.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Er is iets misgegaan: ' . $e->getMessage()]);
        }
    }

    public function cancelLessonSick(Customer $customer, Reservation $reservation)
    {
        try {
            DB::transaction(function () use ($reservation, $customer) {
                // Get the instructor associated with this customer and reservation
                $instructor = $customer->instructors->first();
                
                if ($instructor) {
                    // Send email to customer
                    Mail::to($customer->user->email)
                        ->send(new LessonCancellationSick($reservation, $instructor));
                    
                    // Send email to instructor
                    Mail::to($instructor->user->email)
                        ->send(new LessonCancellationSick($reservation, $instructor));
                }

                // Delete invoice if it exists
                if ($reservation->invoice) {
                    $reservation->invoice->delete();
                }

                // Delete the reservation
                $reservation->delete();
            });

            return back()->with('success', 'Les geannuleerd en e-mails verzonden.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Er is iets misgegaan: ' . $e->getMessage()]);
        }
    }

    public function customerLessons(Customer $customer)
    {
        $customer->load(['user.contact', 'instructors.user.contact']);
        $lessons = Reservation::where('userId', $customer->userId)
            ->with(['package', 'location'])
            ->orderBy('reservationDate', 'desc')
            ->get();

        return view('owner.customers.lessons', compact('customer', 'lessons'));
    }

    public function instructorScheduleDay(Instructor $instructor)
    {
        $date = request('date', now()->toDateString());
        
        $lessons = Reservation::whereIn('userId', $instructor->customers->pluck('userId'))
            ->whereDate('reservationDate', $date)
            ->with(['package', 'location', 'user.contact'])
            ->orderBy('reservationTime')
            ->get();

        return view('owner.instructors.schedule.day', compact('instructor', 'lessons', 'date'));
    }

    public function instructorScheduleWeek(Instructor $instructor)
    {
        $startDate = request('date', now()->startOfWeek()->toDateString());
        $endDate = Carbon::parse($startDate)->endOfWeek()->toDateString();
        
        $lessons = Reservation::whereIn('userId', $instructor->customers->pluck('userId'))
            ->whereBetween('reservationDate', [$startDate, $endDate])
            ->with(['package', 'location', 'user.contact'])
            ->orderBy('reservationDate')
            ->orderBy('reservationTime')
            ->get()
            ->groupBy('reservationDate');

        return view('owner.instructors.schedule.week', compact('instructor', 'lessons', 'startDate'));
    }

    public function instructorScheduleMonth(Instructor $instructor)
    {
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

        return view('owner.instructors.schedule.month', compact('instructor', 'lessons', 'startDate'));
    }

    public function unpaidInvoices()
    {
        $unpaidInvoices = Invoice::where('status', 'unpaid')
            ->with(['reservation.user.contact', 'reservation.package'])
            ->orderBy('dueDate')
            ->get();

        return view('owner.unpaid-invoices', compact('unpaidInvoices'));
    }
}
