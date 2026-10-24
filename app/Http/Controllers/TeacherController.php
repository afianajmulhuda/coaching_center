<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers with filters.
     */
    public function index(Request $request)
    {
        $query = Teacher::with('user');

        if ($request->filled('salary_type')) {
            $query->where('salary_type', $request->salary_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        $teachers = $query->orderBy('id', 'desc')->paginate(10);

        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher profile.
     */
    public function create()
    {
        // Get users who do not have a teacher profile assigned yet
        $availableUsers = User::doesntHave('teacher')->get();

        return view('teachers.create', compact('availableUsers'));
    }

    /**
     * Store a newly created teacher profile in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|integer|unique:teachers,user_id|exists:users,id',
            'full_name'   => 'required|string|max:100',
            'designation' => 'nullable|string|max:50',
            'salary_type' => 'required|in:monthly,per_class',
            'base_salary' => 'nullable|numeric|min:0',
        ]);

        Teacher::create([
            'user_id'     => $request->user_id,
            'full_name'   => $request->full_name,
            'designation' => $request->designation,
            'salary_type' => $request->salary_type,
            'base_salary' => $request->base_salary ?? 0.00,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher profile created successfully.');
    }

    /**
     * Show the form for editing the specified teacher profile.
     */
    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified teacher profile in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'full_name'   => 'required|string|max:100',
            'designation' => 'nullable|string|max:50',
            'salary_type' => 'required|in:monthly,per_class',
            'base_salary' => 'nullable|numeric|min:0',
        ]);

        $teacher->update([
            'full_name'   => $request->full_name,
            'designation' => $request->designation,
            'salary_type' => $request->salary_type,
            'base_salary' => $request->base_salary ?? 0.00,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher profile updated successfully.');
    }

    /**
     * Remove the specified teacher profile from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()->route('teachers.index')->with('success', 'Teacher profile deleted successfully.');
    }
}