<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {   
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'infix' => ['nullable', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'adress' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'dateOfBirth' => ['required', 'date'],
            'bsnNumber' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:255'],
        ]);

        $contact = $request->user()->contact;
        if (!$contact) {
            $contact = $request->user()->contact()->create($validated);
        } else {
            $contact->fill($validated);
            $contact->save();
        }

        return Redirect::route('profile.edit')->with('status', 'contact-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
