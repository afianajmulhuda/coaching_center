<?php

namespace App\Http\Controllers;

use App\Models\ClassRoutine;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ClassRoutineController extends Controller
{
    /**
     * Display a listing of routines with filter capabilities.
     */
    public function index(Request $request)
    {
        $query = ClassRoutine::with(['batch.class', 'subject', 'teacher']);

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        $routines = $query->orderBy('day_of_week', 'asc')
                          ->orderBy('start_time', 'asc')
                          ->paginate(15);

        $batches = Batch::with('class')->get();
        $days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        return view('routines.index', compact('routines', 'batches', 'days'));
    }

    /**
     * Show the form for creating a new routine entry.
     */
    public function create()
    {
        $batches = Batch::with('class')->get();
        $subjects = Subject::all();
        // Assuming teachers have a 'Teacher' role or specific flag; adjust query as needed
        $teachers = User::all();
        $days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        return view('routines.create', compact('batches', 'subjects', 'teachers', 'days'));
    }

    /**
     * Store a newly created routine in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'batch_id'    => 'required|exists:batches,id',
            'subject_id'  => 'required|exists:subjects,id',
            'teacher_id'  => 'required|exists:users,id',
            'day_of_week' => 'required|in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:20',
        ]);

        ClassRoutine::create($request->all());

        return redirect()->route('routines.index')->with('success', 'Routine scheduled successfully.');
    }

    /**
     * Show the form for editing the specified routine entry.
     */
    public function edit(ClassRoutine $routine)
    {
        $batches = Batch::with('class')->get();
        $subjects = Subject::all();
        $teachers = User::all();
        $days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        return view('routines.edit', compact('routine', 'batches', 'subjects', 'teachers', 'days'));
    }

    /**
     * Update the specified routine entry in storage.
     */
    public function update(Request $request, ClassRoutine $routine)
    {
        $request->validate([
            'batch_id'    => 'required|exists:batches,id',
            'subject_id'  => 'required|exists:subjects,id',
            'teacher_id'  => 'required|exists:users,id',
            'day_of_week' => 'required|in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:20',
        ]);

        $routine->update($request->all());

        return redirect()->route('routines.index')->with('success', 'Routine updated successfully.');
    }

    /**
     * Remove the specified routine from storage.
     */
    public function destroy(ClassRoutine $routine)
    {
        $routine->delete();

        return redirect()->route('routines.index')->with('success', 'Routine schedule deleted successfully.');
    }
}