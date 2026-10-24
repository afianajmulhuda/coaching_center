@extends('layouts.master')

@section('title', 'Payment Collections')
@section('page_title', 'Payment History & Receipts')

@section('content')
<div class="row">
  <div class="col-12">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Filters -->
    <div class="card mb-3">
      <div class="card-body">
        <form method="GET" action="{{ route('payment-collections.index') }}" class="row g-3">
          <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Receipt No, Student, Txn Ref..." value="{{ request('search') }}">
          </div>
          <div class="col-md-3">
            <select name="payment_method" class="form-select">
              <option value="">-- All Payment Methods --</option>
              <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
              <option value="bkash" {{ request('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
              <option value="nagad" {{ request('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
              <option value="bank" {{ request('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
            </select>
          </div>
          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter"></i> Filter</button>
            <a href="{{ route('payment-collections.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Collected Payments</h3>
        <a href="{{ route('payment-collections.create') }}" class="btn btn-primary btn-sm ms-auto">
          <i class="bi bi-plus-lg me-1"></i> Collect Payment
        </a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th>Receipt No</th>
                <th>Student</th>
                <th>Fee Details</th>
                <th>Paid Amount</th>
                <th>Method</th>
                <th>Collected By</th>
                <th>Date</th>
                <th style="width: 150px" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($payments as $payment)
                <tr>
                  <td><code>{{ $payment->invoice_no }}</code></td>
                  <td>
                    <span class="fw-bold">{{ $payment->studentFee->student->full_name ?? 'N/A' }}</span><br>
                    <small class="text-muted">{{ $payment->studentFee->student->student_id_no ?? '' }}</small>
                  </td>
                  <td>
                    {{ $payment->studentFee->feeType->title ?? 'N/A' }}
                    <small class="text-muted">({{ $payment->studentFee->billing_month_year ?? '' }})</small>
                  </td>
                  <td><strong class="text-success">${{ number_format($payment->paid_amount, 2) }}</strong></td>
                  <td>
                    <span class="badge text-bg-info text-uppercase">{{ $payment->payment_method }}</span>
                    @if($payment->transaction_ref)
                      <br><small class="text-muted">Ref: {{ $payment->transaction_ref }}</small>
                    @endif
                  </td>
                  <td><small>{{ $payment->collector->name ?? 'System' }}</small></td>
                  <td>{{ $payment->created_at ? $payment->created_at->format('M d, Y h:i A') : 'N/A' }}</td>
                  <td class="text-end">
                    <a href="{{ route('payment-collections.edit', $payment->id) }}" class="btn btn-sm btn-outline-warning me-1">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('payment-collections.destroy', $payment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payment receipt?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-4">No payment collections found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($payments->hasPages())
        <div class="card-footer">
          {{ $payments->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection