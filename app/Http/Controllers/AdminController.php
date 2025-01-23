<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;  // Ensure correct import

class AdminController extends Controller
{
    public function index()
    {
        // Ensure only super-admin can access
        if (!auth()->user() || !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized action.');
        }

        $admins = User::role('admin')->get();
        return view('admin.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign 'admin' role to the created user
        $admin->assignRole('admin');

        return redirect('/dashboard')->with('success', 'Admin created successfully!');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('admin')) {
            $user->delete();
            return redirect('/dashboard')->with('success', 'Admin deleted successfully!');
        }

        return redirect('/dashboard')->with('error', 'Cannot delete this user.');
    }
}
