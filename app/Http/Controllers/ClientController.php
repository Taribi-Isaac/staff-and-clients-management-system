<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        
        $clients = Clients::when($query, function ($q) use ($query) {
            $q->where('client_name', 'LIKE', "%{$query}%")
              ->orWhere('business_name', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('location', 'LIKE', "%{$query}%");
        })->paginate(10);
    
        return view('clients.index', compact('clients'));
    }
    
    public function create()
    {
        return view(
            'clients.create'
        );
    }
public function show($id)
{
    $client = Clients::findOrFail($id);
    return view('clients.show', compact('client'));
}

public function edit($id)
{
    $client = Clients::findOrFail($id);
    return view('clients.edit', compact('client'));
}



public function update(Request $request, $id)
{
    $client = Clients::findOrFail($id);

    $request->validate([
        'client_name' => 'required|string|max:255',
        'business_name' => 'nullable|string|max:255',
        'location' => 'required|string|max:255',
        'account_number' => 'required|string|max:255',
        'dish_serial_number' => 'required|string|max:255',
        'kit_number' => 'required|string|max:255',
        'starlink_id' => 'required|string|max:255',
        'Password' => 'required|string|max:255',
        'subscription_duration' => 'nullable|string|max:255',
        'subscription_start_date' => 'nullable|date',
        'subscription_end_date' => 'nullable|date',
        'email' => 'required|email|unique:clients,email,' . $id,
        'service_address' => 'nullable|string|max:255',
        'account_name' => 'nullable|string|max:255',
        'card_details' => 'nullable|string|max:255',
    ]);

    $client->update($request->all());

    return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
}



public function store(Request $request)
{
    $request->validate([
        'client_name' => 'required|string|max:255',
        'business_name' => 'nullable|string|max:255',
        'location' => 'required|string|max:255',
        'account_number' => 'required|string|max:255',
        'dish_serial_number' => 'required|string|max:255',
        'kit_number' => 'required|string|max:255',
        'starlink_id' => 'required|string|max:255',
        'subscription_duration' => 'nullable|string|max:255',
        'subscription_start_date' => 'nullable|date',
        'subscription_end_date' => 'nullable|date',
        'email' => 'required|email|unique:clients,email',
        'password' => 'required|string|min:8',
        'service_address' => 'nullable|string|max:255',
        'account_name' => 'nullable|string|max:255',
        'card_details' => 'nullable|string|max:255',
    ]);

    $client = new Clients($request->all());

    $client->save();

    return redirect()->route('clients.index')->with('success', 'Client created successfully!');
}







public function destroy(){

}
}
