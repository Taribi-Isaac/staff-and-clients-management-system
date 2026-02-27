<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Partner;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('search');
        $partner = $request->input('partner');

        $stations = Station::when($query, function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('address', 'LIKE', "%{$query}%")
              ->orWhere('state', 'LIKE', "%{$query}%")
              ->orWhere('location', 'LIKE', "%{$query}%");
        })
        ->when($partner, function ($q) use ($partner) {
            $q->where('partner_id', $partner);
        })
        ->with('partner')
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        $partners = Partner::orderBy('name')->get();
        return view('stations.index', compact('stations', 'partners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $partners = Partner::orderBy('name')->get();
        return view('stations.create', compact('partners'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'name' => 'nullable|string|max:255',
            'address' => 'required|string',
            'state' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'assets' => 'nullable|string',
            'primary_contact_name' => 'nullable|string|max:255',
            'primary_contact_email' => 'nullable|email|max:255',
            'primary_contact_phone' => 'nullable|string|max:255',
        ]);

        Station::create($request->all());

        return redirect()->route('stations.index')
            ->with('success', 'Station created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $station = Station::with('partner')->findOrFail($id);
        return view('stations.show', compact('station'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $station = Station::findOrFail($id);
        $partners = Partner::orderBy('name')->get();
        return view('stations.edit', compact('station', 'partners'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $station = Station::findOrFail($id);

        $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'name' => 'nullable|string|max:255',
            'address' => 'required|string',
            'state' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'assets' => 'nullable|string',
            'primary_contact_name' => 'nullable|string|max:255',
            'primary_contact_email' => 'nullable|email|max:255',
            'primary_contact_phone' => 'nullable|string|max:255',
        ]);

        $station->update($request->all());

        return redirect()->route('stations.index')
            ->with('success', 'Station updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->user() || !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized action. Only super-admins can delete stations.');
        }

        $station = Station::findOrFail($id);
        $station->delete();

        return redirect()->route('stations.index')
            ->with('success', 'Station deleted successfully!');
    }
}
