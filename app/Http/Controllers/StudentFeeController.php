<?php

namespace App\Http\Controllers;

use App\Models\StudentFee;
use App\Models\Student;
use App\Models\FeeType;
use Illuminate\Http\Request;

class StudentFeeController extends Controller
{
    /**
     * Display a listing of student fee invoices.
     */
    public function index(Request $request)
    {
        $query = StudentFee::with(['student', 'feeType']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('billing_month_year')) {
            $query->where('billing_month_year', $request->billing_month_year);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('student_id_no', 'like', "%{$search}%");
            });
        }

        $fees = $query->orderBy('id', 'desc')->paginate(10);

        return view('student_fees.index', compact('fees'));
    }

    /**
     * Show the form for creating a new fee invoice.
     */
    public function create()
    {
        $students = Student::all();
        $feeTypes = FeeType::all();

        return view('student_fees.create', compact('students', 'feeTypes'));
    }

    /**
     * Store a newly created fee invoice in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id'         => 'required|exists:students,id',
            'fee_type_id'        => 'required|exists:fee_types,id',
            'billing_month_year' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'amount'             => 'required|numeric|min:0',
            'discount'           => 'nullable|numeric|min:0',
            'due_date'           => 'nullable|date',
            'status'             => 'required|in:unpaid,partially_paid,paid',
        ]);

        StudentFee::create([
            'student_id'         => $request->student_id,
            'fee_type_id'        => $request->fee_type_id,
            'billing_month_year' => $request->billing_month_year,
            'amount'             => $request->amount,
            'discount'           => $request->discount ?? 0.00,
            'due_date'           => $request->due_date,
            'status'             => $request->status,
        ]);

        return redirect()->route('student-fees.index')->with('success', 'Fee invoice generated successfully.');
    }

    /**
     * Show the form for editing the specified fee invoice.
     */
    public function edit(StudentFee $studentFee)
    {
        $students = Student::all();
        $feeTypes = FeeType::all();

        return view('student_fees.edit', compact('studentFee', 'students', 'feeTypes'));
    }

    /**
     * Update the specified fee invoice in storage.
     */
    public function update(Request $request, StudentFee $studentFee)
    {
        $request->validate([
            'student_id'         => 'required|exists:students,id',
            'fee_type_id'        => 'required|exists:fee_types,id',
            'billing_month_year' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'amount'             => 'required|numeric|min:0',
            'discount'           => 'nullable|numeric|min:0',
            'due_date'           => 'nullable|date',
            'status'             => 'required|in:unpaid,partially_paid,paid',
        ]);

        $studentFee->update([
            'student_id'         => $request->student_id,
            'fee_type_id'        => $request->fee_type_id,
            'billing_month_year' => $request->billing_month_year,
            'amount'             => $request->amount,
            'discount'           => $request->discount ?? 0.00,
            'due_date'           => $request->due_date,
            'status'             => $request->status,
        ]);

        return redirect()->route('student-fees.index')->with('success', 'Fee invoice updated successfully.');
    }

    /**
     * Remove the specified fee invoice from storage.
     */
    public function destroy(StudentFee $studentFee)
    {
        $studentFee->delete();

        return redirect()->route('student-fees.index')->with('success', 'Fee invoice deleted successfully.');
    }
}