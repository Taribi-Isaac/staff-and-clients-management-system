@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-3xl font-bold mb-6 text-center">Update Admin</h2>

    <form method="POST" action="{{ route('admin.update', ['admin' => $admin->id]) }}">
        @csrf
        
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Name</label>
            <input type="text" value="{{ old('name', $admin->name) }}" name="name" id="name" class="border-gray-300 rounded w-full {{ $errors->has('name') ? 'border-red-500' : '' }}" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email</label>
            <input type="email" value="{{ old('email', $admin->email) }}" name="email" autocomplete="off" id="email" class="border-gray-300 rounded w-full {{ $errors->has('email') ? 'border-red-500' : '' }}" required>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
    <label for="password" class="block text-gray-700">New Password</label>
    <input type="password" value="{{ old('email', $admin->password) }}" name="password" id="password" class="border-gray-300 rounded w-full">
    @error('password')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="password_confirmation" class="block text-gray-700">Confirm New Password</label>
    <input type="password" value="{{ old('email', $admin->password) }}" name="password_confirmation" id="password_confirmation" class="border-gray-300 rounded w-full">
    @error('password_confirmation')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>


        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Update Admin</button>
    </form>
</div>
@endsection
