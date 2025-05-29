<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;

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
        // Check if customer belongs to instructor
        $instructor = auth()->user()->instructor;
        if (!$instructor->customers->contains($customer->id)) {
            return redirect()->route('instructors.customers.index')
                ->with('error', 'Je hebt geen toegang tot deze klant.');
        }

        $customer->load('user.contact');
        return view('instructors.customers.edit', compact('customer'));
    }

    public function customerUpdate(Request $request, Customer $customer)
    {
        // Check if customer belongs to instructor
        $instructor = auth()->user()->instructor;
        if (!$instructor->customers->contains($customer->id)) {
            return redirect()->route('instructors.customers.index')
                ->with('error', 'Je hebt geen toegang tot deze klant.');
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,'.$customer->user->id,
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'dateOfBirth' => 'required|date',
            'mobile' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $customer->user->update(['email' => $validated['email']]);
            
            $customer->user->contact->update([
                'firstName' => $validated['firstName'],
                'lastName' => $validated['lastName'],
                'adress' => $validated['address'], // Note: DB column is 'adress'
                'city' => $validated['city'],
                'dateOfBirth' => $validated['dateOfBirth'],
                'mobile' => $validated['mobile'],
            ]);

            DB::commit();
            return redirect()->route('instructors.customers.index')
                ->with('success', 'Klantgegevens succesvol bijgewerkt.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Er is iets misgegaan: ' . $e->getMessage()]);
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
}