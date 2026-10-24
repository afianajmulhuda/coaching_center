<?php

namespace App\Http\Controllers;

use App\Models\PaymentCollection;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentCollectionController extends Controller
{
    /**
     * Display a listing of payments collected.
     */
    public function index(Request $request)
    {
        $query = PaymentCollection::with(['studentFee.student', 'collector']);

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhere('transaction_ref', 'like', "%{$search}%")
                  ->orWhereHas('studentFee.student', function ($sq) use ($search) {
                      $sq->where('full_name', 'like', "%{$search}%")
                        ->orWhere('student_id_no', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->orderBy('id', 'desc')->paginate(10);

        return view('payment_collections.index', compact('payments'));
    }

    /**
     * Show the form for recording a new payment collection.
     */
    public function create(Request $request)
    {
        // Get all unpaid or partially paid student fee invoices
        $studentFees = StudentFee::with('student')
            ->whereIn('status', ['unpaid', 'partially_paid'])
            ->get();

        // Generate receipt invoice number
        $latestId = PaymentCollection::max('id') + 1;
        $defaultInvoiceNo = 'PAY-' . date('Ymd') . '-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);

        $selectedFeeId = $request->query('student_fee_id');

        return view('payment_collections.create', compact('studentFees', 'defaultInvoiceNo', 'selectedFeeId'));
    }

    /**
     * Store a newly created payment collection in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_no'      => 'required|string|max:30|unique:payment_collections,invoice_no',
            'student_fee_id'  => 'required|exists:student_fees,id',
            'paid_amount'     => 'required|numeric|min:0.01',
            'payment_method'  => 'required|in:cash,bkash,nagad,bank',
            'transaction_ref' => 'nullable|string|max:100',
        ]);

        $payment = PaymentCollection::create([
            'invoice_no'           => $request->invoice_no,
            'student_fee_id'       => $request->student_fee_id,
            'paid_amount'          => $request->paid_amount,
            'payment_method'       => $request->payment_method,
            'transaction_ref'      => $request->transaction_ref,
            'collected_by_user_id' => Auth::id() ?? 1,
            'created_at'           => now(),
        ]);

        // Automatically update the parent Student Fee invoice status
        $studentFee = StudentFee::find($request->student_fee_id);
        $totalPaid = PaymentCollection::where('student_fee_id', $studentFee->id)->sum('paid_amount');
        $netAmount = max(0, $studentFee->amount - $studentFee->discount);

        if ($totalPaid >= $netAmount) {
            $studentFee->update(['status' => 'paid']);
        } elseif ($totalPaid > 0) {
            $studentFee->update(['status' => 'partially_paid']);
        }

        return redirect()->route('payment-collections.index')->with('success', 'Payment recorded successfully.');
    }

    /**
     * Show the form for editing a payment collection entry.
     */
    public function edit(PaymentCollection $paymentCollection)
    {
        $studentFees = StudentFee::with('student')->get();

        return view('payment_collections.edit', compact('paymentCollection', 'studentFees'));
    }

    /**
     * Update the specified payment collection in storage.
     */
    public function update(Request $request, PaymentCollection $paymentCollection)
    {
        $request->validate([
            'invoice_no'      => 'required|string|max:30|unique:payment_collections,invoice_no,' . $paymentCollection->id,
            'student_fee_id'  => 'required|exists:student_fees,id',
            'paid_amount'     => 'required|numeric|min:0.01',
            'payment_method'  => 'required|in:cash,bkash,nagad,bank',
            'transaction_ref' => 'nullable|string|max:100',
        ]);

        $paymentCollection->update([
            'invoice_no'      => $request->invoice_no,
            'student_fee_id'  => $request->student_fee_id,
            'paid_amount'     => $request->paid_amount,
            'payment_method'  => $request->payment_method,
            'transaction_ref' => $request->transaction_ref,
        ]);

        // Recalculate status for the parent Student Fee invoice
        $studentFee = StudentFee::find($request->student_fee_id);
        $totalPaid = PaymentCollection::where('student_fee_id', $studentFee->id)->sum('paid_amount');
        $netAmount = max(0, $studentFee->amount - $studentFee->discount);

        if ($totalPaid >= $netAmount) {
            $studentFee->update(['status' => 'paid']);
        } elseif ($totalPaid > 0) {
            $studentFee->update(['status' => 'partially_paid']);
        } else {
            $studentFee->update(['status' => 'unpaid']);
        }

        return redirect()->route('payment-collections.index')->with('success', 'Payment collection record updated successfully.');
    }

    /**
     * Remove the specified payment collection from storage.
     */
    public function destroy(PaymentCollection $paymentCollection)
    {
        $feeId = $paymentCollection->student_fee_id;
        $paymentCollection->delete();

        // Recalculate status for the associated Student Fee invoice
        $studentFee = StudentFee::find($feeId);
        if ($studentFee) {
            $totalPaid = PaymentCollection::where('student_fee_id', $feeId)->sum('paid_amount');
            $netAmount = max(0, $studentFee->amount - $studentFee->discount);

            if ($totalPaid >= $netAmount) {
                $studentFee->update(['status' => 'paid']);
            } elseif ($totalPaid > 0) {
                $studentFee->update(['status' => 'partially_paid']);
            } else {
                $studentFee->update(['status' => 'unpaid']);
            }
        }

        return redirect()->route('payment-collections.index')->with('success', 'Payment record deleted successfully.');
    }
}