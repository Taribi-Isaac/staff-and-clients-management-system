@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Inventory Management</h1>

    <!-- Navigation Tabs -->
    <div class="mb-6 flex justify-center">
        <div class="flex gap-2 bg-gray-100 p-1 rounded-lg">
            <a href="{{ route('items.index') }}" class="px-6 py-2 rounded-md {{ request()->routeIs('items.*') ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                Items
            </a>
            <a href="{{ route('suppliers.index') }}" class="px-6 py-2 rounded-md {{ request()->routeIs('suppliers.*') ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                Suppliers
            </a>
            <a href="{{ route('categories.index') }}" class="px-6 py-2 rounded-md {{ request()->routeIs('categories.*') ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                Categories
            </a>
            <a href="{{ route('inventory-transactions.index') }}" class="px-6 py-2 rounded-md {{ request()->routeIs('inventory-transactions.*') ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                Transactions
            </a>
        </div>
    </div>

    <h2 class="text-2xl font-bold mb-6 text-center">Categories Management</h2>

    <!-- Actions -->
    <div class="flex justify-end mb-6">
        <button onclick="document.getElementById('createCategoryModal').classList.remove('hidden')" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">
            Add Category
        </button>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead class="bg-red-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Category Name</th>
                    <th class="px-6 py-4 text-left font-semibold">Code Prefix</th>
                    <th class="px-6 py-4 text-left font-semibold">Description</th>
                    <th class="px-6 py-4 text-left font-semibold">Items Count</th>
                    <th class="px-6 py-4 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $category->name }}</td>
                    <td class="px-6 py-4">
                        @if($category->code_prefix)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm font-mono">{{ $category->code_prefix }}</span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ Str::limit($category->description ?? 'N/A', 50) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-sm">{{ $category->items_count }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button 
                                onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->code_prefix ?? '') }}', '{{ addslashes($category->description ?? '') }}')" 
                                class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">
                                Edit
                            </button>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No categories found. Create your first category!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>

<!-- Create Category Modal -->
<div id="createCategoryModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Create New Category</h3>
            <button onclick="document.getElementById('createCategoryModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                <input type="text" name="name" id="create_name" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., Electronics">
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="create_code_prefix" class="block text-sm font-medium text-gray-700 mb-2">Code Prefix</label>
                <input type="text" name="code_prefix" id="create_code_prefix" maxlength="10" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 font-mono" placeholder="e.g., ELEC">
                <p class="mt-1 text-xs text-gray-500">Used for auto-generating item codes (max 10 characters)</p>
                @error('code_prefix')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="create_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="create_description" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Category description..."></textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('createCategoryModal').classList.add('hidden')" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">Cancel</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">Create</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Edit Category</h3>
            <button onclick="document.getElementById('editCategoryModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="editCategoryForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                <input type="text" name="name" id="edit_name" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="edit_code_prefix" class="block text-sm font-medium text-gray-700 mb-2">Code Prefix</label>
                <input type="text" name="code_prefix" id="edit_code_prefix" maxlength="10" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 font-mono">
                <p class="mt-1 text-xs text-gray-500">Used for auto-generating item codes (max 10 characters)</p>
                @error('code_prefix')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="edit_description" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('editCategoryModal').classList.add('hidden')" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">Cancel</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name, codePrefix, description) {
    document.getElementById('editCategoryForm').action = "{{ url('/categories') }}/" + id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_code_prefix').value = codePrefix;
    document.getElementById('edit_description').value = description;
    document.getElementById('editCategoryModal').classList.remove('hidden');
}

// Close modals when clicking outside
window.onclick = function(event) {
    const createModal = document.getElementById('createCategoryModal');
    const editModal = document.getElementById('editCategoryModal');
    
    if (event.target == createModal) {
        createModal.classList.add('hidden');
    }
    if (event.target == editModal) {
        editModal.classList.add('hidden');
    }
}
</script>
@endsection

