<?php

namespace App\Http\Controllers;

use App\Models\ExamSchedule;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Batch;
use Illuminate\Http\Request;

class ExamScheduleController extends Controller
{
    /**
     * Display a listing of exam schedules.
     */
    public function index(Request $request)
    {
        $query = ExamSchedule::with(['exam.ClassModel', 'subject', 'batch']);

        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $schedules = $query->orderBy('exam_date', 'asc')->paginate(10);
        
        $exams    = Exam::all();
        $batches  = Batch::all();
        $subjects = Subject::all();

        return view('exam_schedules.index', compact('schedules', 'exams', 'batches', 'subjects'));
    }

    /**
     * Show the form for creating a new exam schedule.
     */
    public function create()
    {
        $exams    = Exam::with('ClassModel')->get();
        $subjects = Subject::all();
        $batches  = Batch::all();

        return view('exam_schedules.create', compact('exams', 'subjects', 'batches'));
    }

    /**
     * Store a newly created exam schedule in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'exam_id'     => 'required|exists:exams,id',
            'subject_id'  => 'required|exists:subjects,id',
            'batch_id'    => 'required|exists:batches,id',
            'exam_date'   => 'required|date',
            'start_time'  => 'nullable|date_format:H:i',
            'total_marks' => 'required|numeric|min:1|max:999.99',
            'pass_marks'  => 'required|numeric|min:0|lte:total_marks',
        ]);

        ExamSchedule::create([
            'exam_id'     => $request->exam_id,
            'subject_id'  => $request->subject_id,
            'batch_id'    => $request->batch_id,
            'exam_date'   => $request->exam_date,
            'start_time'  => $request->start_time,
            'total_marks' => $request->total_marks,
            'pass_marks'  => $request->pass_marks,
        ]);

        return redirect()->route('exam-schedules.index')->with('success', 'Exam schedule added successfully.');
    }

    /**
     * Show the form for editing the specified exam schedule.
     */
    public function edit(ExamSchedule $examSchedule)
    {
        $exams    = Exam::with('ClassModel')->get();
        $subjects = Subject::all();
        $batches  = Batch::all();

        return view('exam_schedules.edit', compact('examSchedule', 'exams', 'subjects', 'batches'));
    }

    /**
     * Update the specified exam schedule in storage.
     */
    public function update(Request $request, ExamSchedule $examSchedule)
    {
        $request->validate([
            'exam_id'     => 'required|exists:exams,id',
            'subject_id'  => 'required|exists:subjects,id',
            'batch_id'    => 'required|exists:batches,id',
            'exam_date'   => 'required|date',
            'start_time'  => 'nullable',
            'total_marks' => 'required|numeric|min:1|max:999.99',
            'pass_marks'  => 'required|numeric|min:0|lte:total_marks',
        ]);

        // Format start_time safely for standard H:i
        $formattedTime = $request->start_time ? date('H:i', strtotime($request->start_time)) : null;

        $examSchedule->update([
            'exam_id'     => $request->exam_id,
            'subject_id'  => $request->subject_id,
            'batch_id'    => $request->batch_id,
            'exam_date'   => $request->exam_date,
            'start_time'  => $formattedTime,
            'total_marks' => $request->total_marks,
            'pass_marks'  => $request->pass_marks,
        ]);

        return redirect()->route('exam-schedules.index')->with('success', 'Exam schedule updated successfully.');
    }

    /**
     * Remove the specified exam schedule from storage.
     */
    public function destroy(ExamSchedule $examSchedule)
    {
        $examSchedule->delete();

        return redirect()->route('exam-schedules.index')->with('success', 'Exam schedule deleted successfully.');
    }
}