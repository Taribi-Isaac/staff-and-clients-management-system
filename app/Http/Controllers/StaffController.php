<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:staff,email',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'role' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,leave,suspension',
            'employment_type' => 'required|in:full-time,contract,intern',
            'passport' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'state_of_origin' => 'required|string|max:255',
            'local_government_area' => 'required|string|max:255',
            'home_town' => 'nullable|string|max:255',
            'residential_address' => 'required|string|max:255',
    
            // Guarantor fields
            'guarantor_1_name' => 'nullable|string|max:255',
            'guarantor_1_email' => 'nullable|email|max:255',
            'guarantor_1_phone' => 'nullable|string|max:20',
            'guarantor_1_address' => 'nullable|string|max:255',
    
            'guarantor_2_name' => 'nullable|string|max:255',
            'guarantor_2_email' => 'nullable|email|max:255',
            'guarantor_2_phone' => 'nullable|string|max:20',
            'guarantor_2_address' => 'nullable|string|max:255',
    
            // Bank info fields
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:20',
            'account_name' => 'nullable|string|max:255',
    
            // Submitted documents fields
            'submit_doc_1' => 'nullable|mimes:pdf,doc,docx,jpg,png|max:2048',
            'submit_doc_2' => 'nullable|mimes:pdf,doc,docx,jpg,png|max:2048',
            'submit_doc_3' => 'nullable|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);
    
        // Handle file upload (if passport exists)
        if ($request->hasFile('passport')) {
            $filePath = $request->file('passport')->store('passports', 'public');
            $validatedData['passport'] = $filePath;
        }
    
        $documents = [];
        foreach (['submit_doc_1', 'submit_doc_2', 'submit_doc_3'] as $doc) {
            if ($request->hasFile($doc)) {
                $documents[$doc] = $request->file($doc)->store('documents', 'public');
            }
        }
    
        // Merge validated data and documents into one array
        $staffData = array_merge($validatedData, $documents);
    
        // Store staff data
        $staff = Staff::create($staffData);
    
        return redirect()->route('staff.index')->with('success', 'Staff added successfully!');
    }
    

    public function index(Request $request)
    {
        $search = $request->input('search');
        $staff = Staff::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
        })->paginate(10);

        return view('staff.index', compact('staff'));
    }

    public function show($id)
    {
        $staff = Staff::findOrFail($id);

        return view('staff.show', compact('staff'));
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);

        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|unique:staff,email,' . $id,
            'start_date' => 'required|date',
            'role' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,leave,suspension',
            'employment_type' => 'required|in:full-time,contract,intern',
            'state_of_origin' => 'required|string|max:255',
            'local_government_area' => 'required|string|max:255',
            'home_town' => 'required|string|max:255',
            'residential_address' => 'required|string',
            'guarantor_1_name' => 'nullable|string|max:255',
            'guarantor_1_email' => 'nullable|email|max:255',
            'guarantor_1_phone' => 'nullable|string|max:20',
            'guarantor_1_address' => 'nullable|string|max:255',
            'guarantor_2_name' => 'nullable|string|max:255',
            'guarantor_2_email' => 'nullable|email|max:255',
            'guarantor_2_phone' => 'nullable|string|max:20',
            'guarantor_2_address' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:20',
            'account_name' => 'nullable|string|max:255',
            'submit_doc_1' => 'nullable|mimes:pdf,doc,docx,jpg,png|max:2048',
            'submit_doc_2' => 'nullable|mimes:pdf,doc,docx,jpg,png|max:2048',
            'submit_doc_3' => 'nullable|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $staff = Staff::findOrFail($id);
        $staff->update($request->all());

        return response()->json($staff);
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return response()->json(['message' => 'Staff deleted successfully']);
    }
}
