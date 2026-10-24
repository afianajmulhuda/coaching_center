@extends('layouts.master')

@section('title', 'Edit Fee Invoice')
@section('page_title', 'Edit Fee Invoice')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit Invoice #INV-{{ str_pad($studentFee->id, 5, '0', STR_PAD_LEFT) }}</h3>
      </div>

      <form action="{{ route('student-fees.update', $studentFee->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
              <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                @foreach($students as $student)
                  <option value="{{ $student->id }}" {{ old('student_id', $studentFee->student_id) == $student->id ? 'selected' : '' }}>
                    {{ $student->full_name }} ({{ $student->student_id_no }})
                  </option>
                @endforeach
              </select>
              @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="fee_type_id" class="form-label">Fee Type <span class="text-danger">*</span></label>
              <select name="fee_type_id" id="fee_type_id" class="form-select @error('fee_type_id') is-invalid @enderror" required>
                @foreach($feeTypes as $type)
                  <option value="{{ $type->id }}" {{ old('fee_type_id', $studentFee->fee_type_id) == $type->id ? 'selected' : '' }}>
                    {{ $type->title }}
                  </option>
                @endforeach
              </select>
              @error('fee_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="billing_month_year" class="form-label">Billing Month & Year <span class="text-danger">*</span></label>
              <input type="month" name="billing_month_year" id="billing_month_year" class="form-control @error('billing_month_year') is-invalid @enderror" value="{{ old('billing_month_year', $studentFee->billing_month_year) }}" required />
              @error('billing_month_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="due_date" class="form-label">Due Date</label>
              <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', $studentFee->due_date ? date('Y-m-d', strtotime($studentFee->due_date)) : '') }}" />
              @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label for="amount" class="form-label">Base Amount ($) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $studentFee->amount) }}" min="0" required />
              @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label for="discount" class="form-label">Discount ($)</label>
              <input type="number" step="0.01" name="discount" id="discount" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount', $studentFee->discount) }}" min="0" />
              @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
              <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="unpaid" {{ old('status', $studentFee->status) == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="partially_paid" {{ old('status', $studentFee->status) == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                <option value="paid" {{ old('status', $studentFee->status) == 'paid' ? 'selected' : '' }}>Paid</option>
              </select>
              @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update Invoice</button>
          <a href="{{ route('student-fees.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection