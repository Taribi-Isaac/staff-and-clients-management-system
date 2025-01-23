<?php

namespace App\Http\Controllers;

use App\Models\Issues;
use Illuminate\Http\Request;

class IssuesController extends Controller
{
    public function index(Request $request) {
        $query = $request->input('search');
        
        $issues = Issues::when($query, function ($q) use ($query) {
            $q->where('client_name', 'LIKE', "%{$query}%")
              ->orWhere('kit_number', 'LIKE', "%{$query}%")
              ->orWhere('status', 'LIKE', "%{$query}%");
              
        })->paginate(10);
    
        return view('issues.index', compact('issues'));
    }
    public function create()
    {
        return view(
            'issues.create'
        );
    }

    public function show($id)
{
    $issues = Issues::findOrFail($id);
    return view('issues.show', compact('issues'));
}

public function store(Request $request)
{
    $request->validate([
        'issue_description' => 'required|string|max:255',
        'client_name' => 'nullable|string|max:255',
        'kit_number' => 'required|string|max:255',
        'date' => 'required|string|max:255',
        'status' => 'required|string|max:255',
       
    ]);

    $issues = new Issues($request->all());

    $issues->save();

    return redirect()->route('issues.index')->with('success', 'issue created successfully!');
}
public function update(Request $request, $id)
{
    $issue = Issues::findOrFail($id);
    $request->validate([
        'issue_description' => 'required|string|max:255',
        'client_name' => 'nullable|string|max:255',
        'kit_number' => 'required|string|max:255',
        'date' => 'required|string|max:255',
        'status' => 'required|string|max:255',
       
    ]);


    $issue->update($request->all());

    return redirect()->route('issues.index')->with('success', 'issue updated successfully!');
}







public function destroy(){

}
}
