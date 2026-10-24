<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Display a listing of exams.
     */
    public function index(Request $request)
    {
        $query = Exam::with('ClassModel');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $exams = $query->orderBy('id', 'desc')->paginate(10);
        $classes = ClassModel::all();

        return view('exams.index', compact('exams', 'classes'));
    }

    /**
     * Show the form for creating a new exam.
     */
    public function create()
    {
        $classes = ClassModel::all();

        return view('exams.create', compact('classes'));
    }

    /**
     * Store a newly created exam in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:100',
            'class_id' => 'required|exists:classes,id',
        ]);

        Exam::create([
            'title'    => $request->title,
            'class_id' => $request->class_id,
        ]);

        return redirect()->route('exams.index')->with('success', 'Exam created successfully.');
    }

    /**
     * Show the form for editing the specified exam.
     */
    public function edit(Exam $exam)
    {
        $classes = ClassModel::all();

        return view('exams.edit', compact('exam', 'classes'));
    }

    /**
     * Update the specified exam in storage.
     */
    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'title'    => 'required|string|max:100',
            'class_id' => 'required|exists:classes,id',
        ]);

        $exam->update([
            'title'    => $request->title,
            'class_id' => $request->class_id,
        ]);

        return redirect()->route('exams.index')->with('success', 'Exam updated successfully.');
    }

    /**
     * Remove the specified exam from storage.
     */
    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()->route('exams.index')->with('success', 'Exam deleted successfully.');
    }
}