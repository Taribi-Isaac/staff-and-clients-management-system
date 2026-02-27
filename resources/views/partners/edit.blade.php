@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Edit Partner</h1>

    <form action="{{ route('partners.update', $partner->id) }}" method="POST" class="bg-white p-8 rounded-lg shadow-md">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="mb-6 border-b pb-6">
            <h2 class="text-xl font-semibold mb-4">Company Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $partner->name) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Company Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $partner->email) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Company Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $partner->phone) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="head_office_address" class="block text-sm font-medium text-gray-700 mb-2">Head Office Address</label>
                    <textarea name="head_office_address" id="head_office_address" rows="2" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('head_office_address', $partner->head_office_address) }}</textarea>
                    @error('head_office_address')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="address" id="address" rows="2" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('address', $partner->address) }}</textarea>
                    @error('address')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Primary Contacts -->
        <div class="mb-6 border-b pb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Primary Contacts</h2>
                <button type="button" id="addContact" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">Add Contact</button>
            </div>
            <div id="contactsContainer">
                @foreach($partner->contacts as $index => $contact)
                <div class="contact-row mb-4 p-4 border border-gray-200 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Name *</label>
                            <input type="text" name="contacts[{{ $index }}][name]" value="{{ old("contacts.$index.name", $contact->name) }}" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Full name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" name="contacts[{{ $index }}][phone]" value="{{ old("contacts.$index.phone", $contact->phone) }}" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Phone number">
                        </div>
                        <div class="flex items-end">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="contacts[{{ $index }}][email]" value="{{ old("contacts.$index.email", $contact->email) }}" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Email address">
                            </div>
                            <button type="button" class="remove-contact ml-2 bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition">Remove</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 text-center">
            <button type="submit" class="bg-red-600 text-white px-8 py-3 rounded-md shadow-md hover:bg-red-700 transition text-lg font-semibold">Update Partner</button>
            <a href="{{ route('partners.show', $partner->id) }}" class="ml-4 bg-gray-500 text-white px-8 py-3 rounded-md shadow-md hover:bg-gray-600 transition text-lg font-semibold">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let contactIndex = {{ count($partner->contacts) }};

    // Add new contact
    document.getElementById('addContact').addEventListener('click', function() {
        const container = document.getElementById('contactsContainer');
        const contactRow = document.createElement('div');
        contactRow.className = 'contact-row mb-4 p-4 border border-gray-200 rounded-lg';
        contactRow.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Name *</label>
                    <input type="text" name="contacts[${contactIndex}][name]" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Full name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" name="contacts[${contactIndex}][phone]" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Phone number">
                </div>
                <div class="flex items-end">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="contacts[${contactIndex}][email]" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Email address">
                    </div>
                    <button type="button" class="remove-contact ml-2 bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition">Remove</button>
                </div>
            </div>
        `;
        container.appendChild(contactRow);
        contactIndex++;
    });

    // Remove contact
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-contact')) {
            e.target.closest('.contact-row').remove();
        }
    });
});
</script>
@endsection
