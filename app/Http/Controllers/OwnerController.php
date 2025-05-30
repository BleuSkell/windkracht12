<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Customer;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;

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
}
