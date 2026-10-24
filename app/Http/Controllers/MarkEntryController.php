<?php

namespace App\Http\Controllers;

use App\Models\MarkEntry;
use App\Models\ExamSchedule;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Batch;
use Illuminate\Http\Request;

class MarkEntryController extends Controller
{
    /**
     * Display a listing of mark entries.
     */
    public function index(Request $request)
    {
        $query = MarkEntry::with(['examSchedule.exam', 'examSchedule.subject', 'examSchedule.batch', 'student']);

        if ($request->filled('exam_schedule_id')) {
            $query->where('exam_schedule_id', $request->exam_schedule_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_id_no', 'like', "%{$search}%");
            });
        }

        $markEntries = $query->orderBy('id', 'desc')->paginate(15);
        $schedules   = ExamSchedule::with(['exam', 'subject', 'batch'])->get();

        return view('mark_entries.index', compact('markEntries', 'schedules'));
    }

    /**
     * Show the bulk mark entry form.
     */
    public function create(Request $request)
    {
        $schedules = ExamSchedule::with(['exam', 'subject', 'batch'])->get();
        $selectedSchedule = null;
        $students = collect();
        $existingMarks = [];

        if ($request->filled('exam_schedule_id')) {
            $selectedSchedule = ExamSchedule::with(['exam', 'subject', 'batch'])->find($request->exam_schedule_id);
            
            if ($selectedSchedule) {
                // Fetch students enrolled in the schedule's batch
                $students = Student::where('batch_id', $selectedSchedule->batch_id)
                    ->orderBy('student_id_no', 'asc')
                    ->get();

                // Key existing entries by student_id
                $existingMarks = MarkEntry::where('exam_schedule_id', $selectedSchedule->id)
                    ->get()
                    ->keyBy('student_id');
            }
        }

        return view('mark_entries.create', compact('schedules', 'selectedSchedule', 'students', 'existingMarks'));
    }

    /**
     * Store bulk mark entries in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'exam_schedule_id' => 'required|exists:exam_schedules,id',
            'marks'            => 'required|array',
            'marks.*.student_id' => 'required|exists:students,id',
            'marks.*.obtained_marks' => 'nullable|numeric|min:0|max:999.99',
            'marks.*.is_absent' => 'nullable|boolean',
            'marks.*.remarks'  => 'nullable|string|max:255',
        ]);

        $schedule = ExamSchedule::findOrFail($request->exam_schedule_id);

        foreach ($request->marks as $markData) {
            $isAbsent = isset($markData['is_absent']) && $markData['is_absent'] == 1;
            $obtainedMarks = $isAbsent ? 0.00 : ($markData['obtained_marks'] ?? 0.00);

            // Validate that marks do not exceed total_marks
            if (!$isAbsent && $obtainedMarks > $schedule->total_marks) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(["marks.{$markData['student_id']}" => "Marks cannot exceed the maximum allowed total marks ({$schedule->total_marks})."]);
            }

            MarkEntry::updateOrCreate(
                [
                    'exam_schedule_id' => $schedule->id,
                    'student_id'       => $markData['student_id'],
                ],
                [
                    'obtained_marks'   => $obtainedMarks,
                    'is_absent'        => $isAbsent ? 1 : 0,
                    'remarks'          => $markData['remarks'] ?? null,
                ]
            );
        }

        return redirect()->route('mark-entries.index')->with('success', 'Mark entries saved successfully.');
    }

    /**
     * Show the form for editing a single mark entry.
     */
    public function edit(MarkEntry $markEntry)
    {
        $markEntry->load(['examSchedule.exam', 'examSchedule.subject', 'examSchedule.batch', 'student']);

        return view('mark_entries.edit', compact('markEntry'));
    }

    /**
     * Update the specified mark entry in storage.
     */
    public function update(Request $request, MarkEntry $markEntry)
    {
        $schedule = $markEntry->examSchedule;

        $request->validate([
            'obtained_marks' => 'nullable|numeric|min:0|max:' . $schedule->total_marks,
            'is_absent'      => 'nullable|boolean',
            'remarks'        => 'nullable|string|max:255',
        ]);

        $isAbsent = $request->has('is_absent');

        $markEntry->update([
            'obtained_marks' => $isAbsent ? 0.00 : ($request->obtained_marks ?? 0.00),
            'is_absent'      => $isAbsent ? 1 : 0,
            'remarks'        => $request->remarks,
        ]);

        return redirect()->route('mark-entries.index')->with('success', 'Mark entry updated successfully.');
    }

    /**
     * Remove the specified mark entry from storage.
     */
    public function destroy(MarkEntry $markEntry)
    {
        $markEntry->delete();

        return redirect()->route('mark-entries.index')->with('success', 'Mark entry deleted successfully.');
    }
}