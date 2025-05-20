<?php 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class PasswordSetupController extends Controller
{
    public function show(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Optioneel: check email hash
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        return view('auth.setup-password', ['user' => $user]);
    }

    public function store(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        $request->validate([
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $user->password = Hash::make($request->password);
        $user->email_verified_at = now();
        $user->save();

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
