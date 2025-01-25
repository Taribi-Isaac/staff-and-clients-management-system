@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-3xl font-bold mb-6 text-center">Create Admin</h2>

    <form method="POST" action="{{ route('admin.store') }}">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Name</label>
            <input type="text" name="name" id="name" class="border-gray-300 rounded w-full" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email</label>
            <input type="email" name="email" autocomplete="off" value="" id="email" class="border-gray-300 rounded w-full" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700">Password</label>
            <input type="password" name="password" autocomplete="off" value="" id="password" class="border-gray-300 rounded w-full" required>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="border-gray-300 rounded w-full" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Update Admin</button>
    </form>
</div>
@endsection
