<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Clients;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('search');
        $type = $request->input('type');
        $status = $request->input('status');

        $projects = Project::when($query, function ($q) use ($query) {
            $q->where('project_name', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%")
              ->orWhere('location', 'LIKE', "%{$query}%");
        })
        ->when($type, function ($q) use ($type) {
            $q->where('project_type', $type);
        })
        ->when($status, function ($q) use ($status) {
            $q->where('status', $status);
        })
        ->with(['client', 'assignedUser', 'creator'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Clients::orderBy('client_name')->get();
        $users = User::orderBy('name')->get();
        return view('projects.create', compact('clients', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'project_type' => 'required|in:construction,internet_installation,networking,solar_installation,maintenance,consultation,other',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id',
            'client_name' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'required|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
            'progress' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max per file
        ]);

        $data = $request->except(['photos']);
        $data['created_by'] = auth()->id();

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            $photoPaths = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('projects', 'public');
                $photoPaths[] = $path;
            }
            $data['photos'] = $photoPaths;
        }

        $project = Project::create($data);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::with(['client', 'assignedUser', 'creator'])->findOrFail($id);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = Project::findOrFail($id);
        $clients = Clients::orderBy('client_name')->get();
        $users = User::orderBy('name')->get();
        return view('projects.edit', compact('project', 'clients', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'project_name' => 'required|string|max:255',
            'project_type' => 'required|in:construction,internet_installation,networking,solar_installation,maintenance,consultation,other',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id',
            'client_name' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'required|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
            'progress' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max per file
            'remove_photos' => 'nullable|array',
        ]);

        $data = $request->except(['photos', 'remove_photos']);

        // Handle photo removal
        if ($request->has('remove_photos')) {
            $photosToRemove = $request->input('remove_photos');
            $currentPhotos = $project->photos ?? [];
            foreach ($photosToRemove as $photoToRemove) {
                // Delete file from storage
                if (Storage::disk('public')->exists($photoToRemove)) {
                    Storage::disk('public')->delete($photoToRemove);
                }
                // Remove from array
                $currentPhotos = array_filter($currentPhotos, function($photo) use ($photoToRemove) {
                    return $photo !== $photoToRemove;
                });
            }
            $data['photos'] = array_values($currentPhotos); // Re-index array
        } else {
            $data['photos'] = $project->photos ?? [];
        }

        // Handle new photo uploads
        if ($request->hasFile('photos')) {
            $newPhotoPaths = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('projects', 'public');
                $newPhotoPaths[] = $path;
            }
            $existingPhotos = $data['photos'] ?? [];
            $data['photos'] = array_merge($existingPhotos, $newPhotoPaths);
        }

        $project->update($data);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Only super-admins can delete projects
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized action.');
        }

        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully!');
    }
}
