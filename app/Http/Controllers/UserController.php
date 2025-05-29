<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Contact;

class UserController extends Controller
{
    public function index()
    {   
        $users = User::with(['roles', 'contact'])->get();

        return view('users.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $oldRole = $user->roles->roleName;
        $user->update($request->only('name', 'email'));

        // Handle role change
        if ($request->has('role')) {
            $newRole = Role::findOrFail($request->input('role'));
            $user->roleId = $newRole->id;
            $user->save();

            // Handle customer/instructor table transitions
            if ($oldRole === 'customer' && $newRole->roleName === 'instructor') {
                // Delete from customers table
                $user->customer()->delete();
                // Add to instructors table
                $user->instructor()->create();
            } elseif ($oldRole === 'instructor' && $newRole->roleName === 'customer') {
                // Delete from instructors table
                $user->instructor()->delete();
                // Add to customers table
                $user->customer()->create();
            }
        }

        return redirect()->route('users.index')->with('success', 'Gebruiker bijgewerkt.');
    }
}
