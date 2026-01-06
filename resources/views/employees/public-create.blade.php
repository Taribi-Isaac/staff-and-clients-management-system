<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration - Raslordeck Limited</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Employee Registration</h1>
                    <p class="text-gray-600">Raslordeck Limited</p>
                    <p class="text-sm text-gray-500 mt-2">Please fill in all required information accurately</p>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('employee.public.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Personal Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Personal Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                @error('phone')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="passport" class="block text-sm font-medium text-gray-700 mb-2">Passport Photo</label>
                                <input type="file" id="passport" name="passport" accept="image/jpeg,image/png,image/jpg" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                <p class="text-xs text-gray-500 mt-1">Max size: 2MB (JPEG, PNG, JPG)</p>
                                @error('passport')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Employment Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Employment Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role/Position *</label>
                                <input type="text" id="role" name="role" value="{{ old('role') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., Rider, Manager, etc.">
                                @error('role')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="employment_type" class="block text-sm font-medium text-gray-700 mb-2">Employment Type *</label>
                                <select id="employment_type" name="employment_type" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="">-- Select Type --</option>
                                    <option value="full-time" {{ old('employment_type') == 'full-time' ? 'selected' : '' }}>Full-Time</option>
                                    <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="intern" {{ old('employment_type') == 'intern' ? 'selected' : '' }}>Intern</option>
                                </select>
                                @error('employment_type')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                @error('start_date')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date (if applicable)</label>
                                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                @error('end_date')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select id="status" name="status" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="">-- Select Status --</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="leave" {{ old('status') == 'leave' ? 'selected' : '' }}>Leave</option>
                                    <option value="suspension" {{ old('status') == 'suspension' ? 'selected' : '' }}>Suspension</option>
                                </select>
                                @error('status')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Address Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="state_of_origin" class="block text-sm font-medium text-gray-700 mb-2">State of Origin *</label>
                                <input type="text" id="state_of_origin" name="state_of_origin" value="{{ old('state_of_origin') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                @error('state_of_origin')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="local_government_area" class="block text-sm font-medium text-gray-700 mb-2">Local Government Area *</label>
                                <input type="text" id="local_government_area" name="local_government_area" value="{{ old('local_government_area') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                @error('local_government_area')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="home_town" class="block text-sm font-medium text-gray-700 mb-2">Home Town</label>
                                <input type="text" id="home_town" name="home_town" value="{{ old('home_town') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                @error('home_town')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="residential_address" class="block text-sm font-medium text-gray-700 mb-2">Residential Address *</label>
                                <textarea id="residential_address" name="residential_address" rows="2" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('residential_address') }}</textarea>
                                @error('residential_address')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Guarantor Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Guarantor Information</h2>
                        
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Guarantor 1</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="guarantor_1_name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                <input type="text" id="guarantor_1_name" name="guarantor_1_name" value="{{ old('guarantor_1_name') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label for="guarantor_1_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="guarantor_1_email" name="guarantor_1_email" value="{{ old('guarantor_1_email') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label for="guarantor_1_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                <input type="text" id="guarantor_1_phone" name="guarantor_1_phone" value="{{ old('guarantor_1_phone') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label for="guarantor_1_address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea id="guarantor_1_address" name="guarantor_1_address" rows="2" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('guarantor_1_address') }}</textarea>
                            </div>
                        </div>

                        <h3 class="text-lg font-medium text-gray-800 mb-3">Guarantor 2</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="guarantor_2_name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                <input type="text" id="guarantor_2_name" name="guarantor_2_name" value="{{ old('guarantor_2_name') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label for="guarantor_2_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="guarantor_2_email" name="guarantor_2_email" value="{{ old('guarantor_2_email') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label for="guarantor_2_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                <input type="text" id="guarantor_2_phone" name="guarantor_2_phone" value="{{ old('guarantor_2_phone') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label for="guarantor_2_address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea id="guarantor_2_address" name="guarantor_2_address" rows="2" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('guarantor_2_address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Bank Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                                <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                                <input type="text" id="account_number" name="account_number" value="{{ old('account_number') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div class="md:col-span-2">
                                <label for="account_name" class="block text-sm font-medium text-gray-700 mb-2">Account Name</label>
                                <input type="text" id="account_name" name="account_name" value="{{ old('account_name') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Supporting Documents</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="submit_doc_1" class="block text-sm font-medium text-gray-700 mb-2">Document 1</label>
                                <input type="file" id="submit_doc_1" name="submit_doc_1" accept=".pdf,.doc,.docx,.jpg,.png" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, JPG, PNG (Max: 2MB)</p>
                            </div>
                            <div>
                                <label for="submit_doc_2" class="block text-sm font-medium text-gray-700 mb-2">Document 2</label>
                                <input type="file" id="submit_doc_2" name="submit_doc_2" accept=".pdf,.doc,.docx,.jpg,.png" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, JPG, PNG (Max: 2MB)</p>
                            </div>
                            <div>
                                <label for="submit_doc_3" class="block text-sm font-medium text-gray-700 mb-2">Document 3</label>
                                <input type="file" id="submit_doc_3" name="submit_doc_3" accept=".pdf,.doc,.docx,.jpg,.png" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, JPG, PNG (Max: 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center gap-4 pt-6">
                        <button type="submit" class="bg-red-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-700 transition shadow-md">
                            Submit Registration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

