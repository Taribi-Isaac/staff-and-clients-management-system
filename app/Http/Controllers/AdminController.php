<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Http\Requests\AdminUpdateRequest; // Import the validation request class

class AdminController extends Controller
{
    public function index()
    {
        // Ensure only super-admin can access
        if (!auth()->user() || !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized action.');
        }

        $admins = User::role('admin')->paginate(10);
        return view('admin.index', compact('admins'));
    }

    public function edit($id)
{
    $admin = User::findOrFail($id);  // Ensure you are retrieving the admin correctly
    return view('admin.edit', compact('admin'));
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
            'role' => 'required|in:admin,super-admin',
        ]);
    
        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        $admin->assignRole($request->role);
    
        return redirect('/home')->with('success', 'Admin created successfully!');
    }
    

    public function update(AdminUpdateRequest $request, User $admin)
    {
        $validated = $request->validated();
    
        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $admin->password,
        ]);
    
        return redirect()->route('admin.index')->with('success', 'Admin updated successfully!');
    }
    

    public function destroy(User $admin)
    {
        if ($admin->hasRole('admin')) {
            $admin->delete();
            return redirect()->route('admin.index')->with('success', 'Admin deleted successfully!');
        }
    
        return redirect()->route('admin.index')->with('error', 'Cannot delete this user.');
    }
    
}
