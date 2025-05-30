<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Role;
use App\Models\Contact;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        ]);
        
        // default role = customer Id
        $defaultRole = Role::where('roleName', 'customer')->value('id');

        $user = User::create([
            'roleId' => $defaultRole,
            'email' => $request->email,
            'password' => '',
        ]);

        Contact::create([
            'userId' => $user->id,
            'firstName' => '',
            'infix' => null,
            'lastName' => '',
            'adress' => '',
            'city' => '',
            'dateOfBirth' => null,
            'bsnNumber' => '',
            'mobile' => '',
        ]);

        // Create customer record
        $user->customer()->create();

        event(new Registered($user));

        return redirect(route('verification.notice'));
    }
}
