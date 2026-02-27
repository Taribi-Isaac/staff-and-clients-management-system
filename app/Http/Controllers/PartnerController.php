<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\PartnerContact;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('search');

        $partners = Partner::when($query, function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('phone', 'LIKE', "%{$query}%");
        })
        ->with(['contacts', 'stations'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('partners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'head_office_address' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'required_with:contacts|string|max:255',
            'contacts.*.phone' => 'nullable|string|max:255',
            'contacts.*.email' => 'nullable|email|max:255',
        ]);

        $partner = Partner::create([
            'name' => $request->name,
            'head_office_address' => $request->head_office_address,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Create contacts if provided
        if ($request->has('contacts') && is_array($request->contacts)) {
            foreach ($request->contacts as $contactData) {
                if (!empty($contactData['name'])) {
                    PartnerContact::create([
                        'partner_id' => $partner->id,
                        'name' => $contactData['name'],
                        'phone' => $contactData['phone'] ?? null,
                        'email' => $contactData['email'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('partners.index')
            ->with('success', 'Partner created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $partner = Partner::with(['contacts', 'stations'])->findOrFail($id);
        return view('partners.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $partner = Partner::with('contacts')->findOrFail($id);
        return view('partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $partner = Partner::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'head_office_address' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'required_with:contacts|string|max:255',
            'contacts.*.phone' => 'nullable|string|max:255',
            'contacts.*.email' => 'nullable|email|max:255',
        ]);

        $partner->update([
            'name' => $request->name,
            'head_office_address' => $request->head_office_address,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Handle contacts update
        if ($request->has('contacts') && is_array($request->contacts)) {
            // Delete existing contacts
            $partner->contacts()->delete();
            
            // Create new contacts
            foreach ($request->contacts as $contactData) {
                if (!empty($contactData['name'])) {
                    PartnerContact::create([
                        'partner_id' => $partner->id,
                        'name' => $contactData['name'],
                        'phone' => $contactData['phone'] ?? null,
                        'email' => $contactData['email'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('partners.index')
            ->with('success', 'Partner updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->user() || !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized action. Only super-admins can delete partners.');
        }

        $partner = Partner::findOrFail($id);
        
        // Check if partner has stations
        if ($partner->stations()->count() > 0) {
            return redirect()->route('partners.index')
                ->with('error', 'Cannot delete partner. This partner has stations. Please delete the stations first.');
        }

        $partner->delete();

        return redirect()->route('partners.index')
            ->with('success', 'Partner deleted successfully!');
    }
}
