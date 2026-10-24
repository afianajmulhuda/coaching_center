<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\ClassModel;
use App\Models\Batch;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of students with search and filters.
     */
    public function index(Request $request)
    {
        $query = Student::with(['user', 'class', 'batch']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('student_id_no', 'like', "%{$search}%")
                  ->orWhere('guardian_phone', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('id', 'desc')->paginate(10);
        $classes = ClassModel::all();
        $batches = Batch::all();

        return view('students.index', compact('students', 'classes', 'batches'));
    }

    /**
     * Show the form for creating a new student profile.
     */
    public function create()
    {
        // Get users without an assigned student profile
        $availableUsers = User::doesntHave('student')->doesntHave('teacher')->get();
        $classes = ClassModel::all();
        $batches = Batch::all();

        // Default Student ID pattern suggestion
        $latestId = Student::max('id') + 1;
        $defaultStudentId = 'CC-' . date('Y') . '-' . str_pad($latestId, 3, '0', STR_PAD_LEFT);

        return view('students.create', compact('availableUsers', 'classes', 'batches', 'defaultStudentId'));
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'        => 'required|integer|unique:students,user_id|exists:users,id',
            'student_id_no'  => 'required|string|max:20|unique:students,student_id_no',
            'full_name'      => 'required|string|max:100',
            'guardian_name'  => 'required|string|max:100',
            'guardian_phone' => 'required|string|max:15',
            'class_id'       => 'required|exists:classes,id',
            'batch_id'       => 'required|exists:batches,id',
            'admission_date' => 'required|date',
        ]);

        Student::create($request->all());

        return redirect()->route('students.index')->with('success', 'Student profile created successfully.');
    }

    /**
     * Show the form for editing the specified student profile.
     */
    public function edit(Student $student)
    {
        $classes = ClassModel::all();
        $batches = Batch::where('class_id', $student->class_id)->get();

        return view('students.edit', compact('student', 'classes', 'batches'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'student_id_no'  => 'required|string|max:20|unique:students,student_id_no,' . $student->id,
            'full_name'      => 'required|string|max:100',
            'guardian_name'  => 'required|string|max:100',
            'guardian_phone' => 'required|string|max:15',
            'class_id'       => 'required|exists:classes,id',
            'batch_id'       => 'required|exists:batches,id',
            'admission_date' => 'required|date',
        ]);

        $student->update($request->except('user_id'));

        return redirect()->route('students.index')->with('success', 'Student profile updated successfully.');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student profile deleted successfully.');
    }
}