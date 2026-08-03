<?php

namespace App\Http\Controllers;

use App\Models\ParentProfile;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ParentProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $parents = ParentProfile::query()
            ->with(['user', 'school'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhere('father_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('parents.index', compact('parents', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::orderBy('name')->get();

        return view('parents.create', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'school_id' => 'required|exists:schools,id',
            'father_name' => 'required|max:255',
            'mother_name' => 'nullable|max:255',
            'phone' => 'required|max:20',
            'alternate_phone' => 'nullable|max:20',
            'address' => 'required',
            'occupation' => 'nullable|max:255',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                ]);

                $user->assignRole('Parent');

                ParentProfile::create([
                    'user_id' => $user->id,
                    'school_id' => $validated['school_id'],
                    'father_name' => $validated['father_name'],
                    'mother_name' => $validated['mother_name'] ?? null,
                    'phone' => $validated['phone'],
                    'alternate_phone' => $validated['alternate_phone'] ?? null,
                    'address' => $validated['address'],
                    'occupation' => $validated['occupation'] ?? null,
                ]);
            });
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create parent profile.']);
        }

        return redirect()
            ->route('parents.index')
            ->with('success', 'Parent profile created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ParentProfile $parentProfile)
    {
        $parentProfile->load(['user', 'school']);

        return view('parents.show', compact('parentProfile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParentProfile $parentProfile)
    {
        $parentProfile->load(['user', 'school']);
        $schools = School::orderBy('name')->get();

        return view('parents.edit', compact('parentProfile', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ParentProfile $parentProfile)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,'.$parentProfile->user_id,
            'password' => 'nullable|min:8',
            'school_id' => 'required|exists:schools,id',
            'father_name' => 'required|max:255',
            'mother_name' => 'nullable|max:255',
            'phone' => 'required|max:20',
            'alternate_phone' => 'nullable|max:20',
            'address' => 'required',
            'occupation' => 'nullable|max:255',
        ]);

        try {
            DB::transaction(function () use ($validated, $parentProfile) {
                $parentProfile->user->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'] ?? $parentProfile->user->password,
                ]);

                $parentProfile->update([
                    'school_id' => $validated['school_id'],
                    'father_name' => $validated['father_name'],
                    'mother_name' => $validated['mother_name'] ?? null,
                    'phone' => $validated['phone'],
                    'alternate_phone' => $validated['alternate_phone'] ?? null,
                    'address' => $validated['address'],
                    'occupation' => $validated['occupation'] ?? null,
                ]);
            });
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update parent profile.']);
        }

        return redirect()
            ->route('parents.index')
            ->with('success', 'Parent profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParentProfile $parentProfile)
    {
        try {
            DB::transaction(function () use ($parentProfile) {
                $parentProfile->user->delete();
                $parentProfile->delete();
            });
        } catch (Throwable $e) {
            return back()
                ->withErrors(['error' => 'Failed to delete parent profile.']);
        }

        return redirect()
            ->route('parents.index')
            ->with('success', 'Parent profile deleted successfully.');
    }
}
