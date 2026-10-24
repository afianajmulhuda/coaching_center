<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of subjects with filters.
     */
    public function index(Request $request)
    {
        $query = Subject::with('class');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject_name', 'like', "%{$search}%")
                  ->orWhere('subject_code', 'like', "%{$search}%");
            });
        }

        $subjects = $query->orderBy('class_id', 'asc')->paginate(10);
        $classes = ClassModel::all();

        return view('subjects.index', compact('subjects', 'classes'));
    }

    /**
     * Show the form for creating a new subject.
     */
    public function create()
    {
        $classes = ClassModel::all();
        return view('subjects.create', compact('classes'));
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id'     => 'required|exists:classes,id',
            'subject_code' => 'nullable|string|max:20',
            'subject_name' => 'required|string|max:100',
        ]);

        Subject::create([
            'class_id'     => $request->class_id,
            'subject_code' => $request->subject_code,
            'subject_name' => $request->subject_name,
        ]);

        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
    }

    /**
     * Show the form for editing the specified subject.
     */
    public function edit(Subject $subject)
    {
        $classes = ClassModel::all();
        return view('subjects.edit', compact('subject', 'classes'));
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'class_id'     => 'required|exists:classes,id',
            'subject_code' => 'nullable|string|max:20',
            'subject_name' => 'required|string|max:100',
        ]);

        $subject->update([
            'class_id'     => $request->class_id,
            'subject_code' => $request->subject_code,
            'subject_name' => $request->subject_name,
        ]);

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}