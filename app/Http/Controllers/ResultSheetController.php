<?php

namespace App\Http\Controllers;

use App\Models\ResultSheet;
use App\Models\Exam;
use App\Models\Student;
use App\Models\ExamSchedule;
use App\Models\MarkEntry;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultSheetController extends Controller
{
    /**
     * Display a listing of results.
     */
    public function index(Request $request)
    {
        $query = ResultSheet::with(['exam.ClassModel', 'student.batch']);

        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->filled('batch_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('batch_id', $request->batch_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_id_no', 'like', "%{$search}%");
            });
        }

        $results = $query->orderBy('position_in_batch', 'asc')->paginate(15);
        $exams   = Exam::with('ClassModel')->get();
        $batches = Batch::all();

        return view('result_sheets.index', compact('results', 'exams', 'batches'));
    }

    /**
     * Show form to generate/calculate results.
     */
    public function create()
    {
        $exams   = Exam::with('ClassModel')->get();
        $batches = Batch::all();

        return view('result_sheets.create', compact('exams', 'batches'));
    }

    /**
     * Calculate and store consolidated results for an exam.
     */
    public function store(Request $request)
    {
        $request->validate([
            'exam_id'  => 'required|exists:exams,id',
            'batch_id' => 'required|exists:batches,id',
        ]);

        $examId  = $request->exam_id;
        $batchId = $request->batch_id;

        // Retrieve schedules for this exam & batch
        $schedules = ExamSchedule::where('exam_id', $examId)
            ->where('batch_id', $batchId)
            ->get();

        if ($schedules->isEmpty()) {
            return redirect()->back()->withErrors(['exam_id' => 'No exam schedules found for the selected exam and batch.']);
        }

        $scheduleIds = $schedules->pluck('id');
        $students    = Student::where('batch_id', $batchId)->get();

        if ($students->isEmpty()) {
            return redirect()->back()->withErrors(['batch_id' => 'No students found in the selected batch.']);
        }

        DB::transaction(function () use ($examId, $scheduleIds, $students) {
            $studentScores = [];

            foreach ($students as $student) {
                $marks = MarkEntry::whereIn('exam_schedule_id', $scheduleIds)
                    ->where('student_id', $student->id)
                    ->get();

                $totalObtained = 0;
                $hasFailed = false;
                $isAbsentAny = false;

                foreach ($marks as $mark) {
                    if ($mark->is_absent) {
                        $isAbsentAny = true;
                    }
                    
                    $totalObtained += $mark->obtained_marks;
                    
                    // Check against pass marks if applicable
                    $passMarks = $mark->examSchedule->pass_marks ?? 0;
                    if ($mark->obtained_marks < $passMarks || $mark->is_absent) {
                        $hasFailed = true;
                    }
                }

                // If not all subject marks are entered, treat remaining as 0
                $maxPossibleSubjects = count($scheduleIds);
                if ($marks->count() < $maxPossibleSubjects) {
                    $hasFailed = true;
                }

                // Calculate percentage based on maximum marks sum
                $totalMaxMarks = ExamSchedule::whereIn('id', $scheduleIds)->sum('total_marks') ?: 1;
                $percentage    = ($totalObtained / $totalMaxMarks) * 100;

                // Grade & GPA logic
                if ($hasFailed) {
                    $gpa   = 0.00;
                    $grade = 'F';
                } else {
                    [$gpa, $grade] = $this->calculateGpaAndGrade($percentage);
                }

                $studentScores[] = [
                    'student_id'     => $student->id,
                    'total_obtained' => $totalObtained,
                    'gpa'            => $gpa,
                    'grade'          => $grade,
                ];
            }

            // Rank students in batch by total_obtained desc, then GPA desc
            usort($studentScores, function ($a, $b) {
                if ($a['total_obtained'] == $b['total_obtained']) {
                    return $b['gpa'] <=> $a['gpa'];
                }
                return $b['total_obtained'] <=> $a['total_obtained'];
            });

            // Upsert result sheet records with positions
            foreach ($studentScores as $index => $score) {
                ResultSheet::updateOrCreate(
                    [
                        'exam_id'    => $examId,
                        'student_id' => $score['student_id'],
                    ],
                    [
                        'total_obtained'    => $score['total_obtained'],
                        'gpa'               => $score['gpa'],
                        'grade'             => $score['grade'],
                        'position_in_batch' => $index + 1,
                    ]
                );
            }
        });

        return redirect()->route('result-sheets.index')->with('success', 'Results generated successfully and ranked by batch.');
    }

    /**
     * Display a single student's consolidated result sheet / mark card.
     */
    public function show(ResultSheet $resultSheet)
    {
        $resultSheet->load(['exam.ClassModel', 'student.batch']);

        // Fetch subject breakdown
        $schedules = ExamSchedule::where('exam_id', $resultSheet->exam_id)
            ->where('batch_id', $resultSheet->student->batch_id)
            ->with('subject')
            ->get();

        $scheduleIds = $schedules->pluck('id');

        $marks = MarkEntry::whereIn('exam_schedule_id', $scheduleIds)
            ->where('student_id', $resultSheet->student_id)
            ->get()
            ->keyBy('exam_schedule_id');

        return view('result_sheets.show', compact('resultSheet', 'schedules', 'marks'));
    }

    /**
     * Show form for editing specific result sheet details.
     */
    public function edit(ResultSheet $resultSheet)
    {
        $resultSheet->load(['exam', 'student']);

        return view('result_sheets.edit', compact('resultSheet'));
    }

    /**
     * Update specified result sheet.
     */
    public function update(Request $request, ResultSheet $resultSheet)
    {
        $request->validate([
            'total_obtained'    => 'required|numeric|min:0',
            'gpa'               => 'nullable|numeric|min:0|max:5.00',
            'grade'             => 'nullable|string|max:5',
            'position_in_batch' => 'nullable|integer|min:1',
            'is_published'      => 'nullable|boolean',
        ]);

        $isPublished = $request->has('is_published');

        $resultSheet->update([
            'total_obtained'    => $request->total_obtained,
            'gpa'               => $request->gpa,
            'grade'             => $request->grade,
            'position_in_batch' => $request->position_in_batch,
            'is_published'      => $isPublished ? 1 : 0,
            'published_at'      => $isPublished ? ($resultSheet->published_at ?? now()) : null,
        ]);

        return redirect()->route('result-sheets.index')->with('success', 'Result sheet updated successfully.');
    }

    /**
     * Toggle bulk publish/unpublish status for an exam.
     */
    public function togglePublish(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'action'  => 'required|in:publish,unpublish',
        ]);

        $isPublish = $request->action === 'publish';

        ResultSheet::where('exam_id', $request->exam_id)->update([
            'is_published' => $isPublish ? 1 : 0,
            'published_at' => $isPublish ? now() : null,
        ]);

        $statusMessage = $isPublish ? 'published' : 'unpublished';

        return redirect()->route('result-sheets.index')->with('success', "All results for this exam have been {$statusMessage}.");
    }

    /**
     * Remove the specified result sheet.
     */
    public function destroy(ResultSheet $resultSheet)
    {
        $resultSheet->delete();

        return redirect()->route('result-sheets.index')->with('success', 'Result entry deleted successfully.');
    }

    /**
     * Helper to compute GPA & Letter Grade from percentage.
     */
    private function calculateGpaAndGrade(float $percentage): array
    {
        if ($percentage >= 80) {
            return [5.00, 'A+'];
        } elseif ($percentage >= 70) {
            return [4.00, 'A'];
        } elseif ($percentage >= 60) {
            return [3.50, 'A-'];
        } elseif ($percentage >= 50) {
            return [3.00, 'B'];
        } elseif ($percentage >= 40) {
            return [2.00, 'C'];
        } elseif ($percentage >= 33) {
            return [1.00, 'D'];
        }

        return [0.00, 'F'];
    }
}