<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    public function index()
    {   
        $users = User::with('roles')->get();

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
        $user->update($request->only('name', 'email'));

        // Handle roles if necessary
        if ($request->has('role')) {
            $user->roleId = $request->input('role');
            $user->save();
        }

        return redirect()->route('users.index')->with('success', 'Gebruiker bijgewerkt.');
    }
}
