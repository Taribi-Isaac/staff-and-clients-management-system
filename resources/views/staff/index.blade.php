@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">All Staff</h1>

    <!-- Search Input -->
    <div class="flex justify-between mb-6">
        <form action="{{ route('staff.index') }}" method="GET">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Quick Search"
                class="w-100 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-3 rounded-md shadow-md hover:bg-blue-600 transition">Search</button>
        </form>
        <div class="ml-2 bg-blue-500 text-white px-4 py-3 rounded-md shadow-md hover:bg-blue-600 transition">
            <a href="{{ route('staff.create') }}">Create New</a>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold text-gray-700">S/N</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Name</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Phone</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Email</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Role</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Passport</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($staff as $index => $member)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">{{ $member->name }}</td>
                    <td class="px-6 py-4">{{ $member->phone }}</td>
                    <td class="px-6 py-4">{{ $member->email }}</td>
                    <td class="px-6 py-4">{{ $member->role }}</td>
                    <td class="px-6 py-4">
    <img src="{{ $member->passport ? asset('storage/passports/' . $member->passport) : asset('images/user.jpg') }}" 
         alt="Passport" 
         class="h-16 w-16 rounded-full object-cover">
</td>

                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $member->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($member->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 flex flex-col justify-between items-center space-x-2">
                        <a href="{{ route('staff.show', $member->id) }}" class="bg-blue-500 text-white px-4 py-2 mb-1 rounded-md shadow-md hover:bg-blue-600 transition">View</a>

                        <a href="{{ route('staff.edit', $member->id) }}" class="bg-yellow-500 text-white px-4 py-2 mb-1 rounded-md shadow-md hover:bg-yellow-600 transition">Update</a>

                        <form action="{{ route('staff.destroy', $member->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-600 transition" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($staff->isEmpty())
            <p class="text-center text-gray-500 mt-4">No staff found.</p>
        @endif
    </div>

    <!-- Pagination Links -->
    <div class="mt-6">
        {{ $staff->appends(['search' => request('search')])->links('pagination::tailwind') }}
    </div>

</div>
@endsection
