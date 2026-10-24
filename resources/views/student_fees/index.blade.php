@extends('layouts.master')

@section('title', 'Student Invoices')
@section('page_title', 'Student Invoices & Dues')

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
        <form method="GET" action="{{ route('student-fees.index') }}" class="row g-3">
          <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search Student Name / ID..." value="{{ request('search') }}">
          </div>
          <div class="col-md-3">
            <input type="month" name="billing_month_year" class="form-control" value="{{ request('billing_month_year') }}">
          </div>
          <div class="col-md-3">
            <select name="status" class="form-select">
              <option value="">-- All Statuses --</option>
              <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
              <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
              <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
          </div>
          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter"></i> Filter</button>
            <a href="{{ route('student-fees.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Fee Records</h3>
        <a href="{{ route('student-fees.create') }}" class="btn btn-primary btn-sm ms-auto">
          <i class="bi bi-plus-lg me-1"></i> Create Fee Invoice
        </a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th>Invoice ID</th>
                <th>Student</th>
                <th>Fee Type</th>
                <th>Month</th>
                <th>Amount (Net)</th>
                <th>Due Date</th>
                <th>Status</th>
                <th style="width: 150px" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($fees as $fee)
                <tr>
                  <td><code>#INV-{{ str_pad($fee->id, 5, '0', STR_PAD_LEFT) }}</code></td>
                  <td>
                    <span class="fw-bold">{{ $fee->student->full_name ?? 'N/A' }}</span><br>
                    <small class="text-muted">{{ $fee->student->student_id_no ?? '' }}</small>
                  </td>
                  <td>{{ $fee->feeType->title ?? 'N/A' }}</td>
                  <td><span class="badge text-bg-dark">{{ $fee->billing_month_year }}</span></td>
                  <td>
                    <strong>${{ number_format($fee->net_amount, 2) }}</strong>
                    @if($fee->discount > 0)
                      <br><small class="text-danger">(-${{ number_format($fee->discount, 2) }} disc)</small>
                    @endif
                  </td>
                  <td>{{ $fee->due_date ?date('M d, Y', strtotime($fee->due_date)) : 'N/A' }}</td>

                  <td>
                    @if($fee->status === 'paid')
                      <span class="badge text-bg-success">Paid</span>
                    @elseif($fee->status === 'partially_paid')
                      <span class="badge text-bg-warning">Partially Paid</span>
                    @else
                      <span class="badge text-bg-danger">Unpaid</span>
                    @endif
                  </td>
                  <td class="text-end">
                    <a href="{{ route('student-fees.edit', $fee->id) }}" class="btn btn-sm btn-outline-warning me-1">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('student-fees.destroy', $fee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this invoice?')">
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
                  <td colspan="8" class="text-center py-4">No fee invoices found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($fees->hasPages())
        <div class="card-footer">
          {{ $fees->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection