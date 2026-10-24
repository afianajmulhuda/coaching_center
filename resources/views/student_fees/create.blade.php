@extends('layouts.master')

@section('title', 'Create Fee Invoice')
@section('page_title', 'Create Fee Invoice')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Generate Student Fee Invoice</h3>
      </div>

      <form action="{{ route('student-fees.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
              <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                <option value="">-- Select Student --</option>
                @foreach($students as $student)
                  <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                    {{ $student->full_name }} ({{ $student->student_id_no }})
                  </option>
                @endforeach
              </select>
              @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="fee_type_id" class="form-label">Fee Type <span class="text-danger">*</span></label>
              <select name="fee_type_id" id="fee_type_id" class="form-select @error('fee_type_id') is-invalid @enderror" required>
                <option value="">-- Select Fee Type --</option>
                @foreach($feeTypes as $type)
                  <option value="{{ $type->id }}" data-amount="{{ $type->default_amount }}" {{ old('fee_type_id') == $type->id ? 'selected' : '' }}>
                    {{ $type->title }} (${{ number_format($type->default_amount, 2) }})
                  </option>
                @endforeach
              </select>
              @error('fee_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="billing_month_year" class="form-label">Billing Month & Year <span class="text-danger">*</span></label>
              <input type="month" name="billing_month_year" id="billing_month_year" class="form-control @error('billing_month_year') is-invalid @enderror" value="{{ old('billing_month_year', date('Y-m')) }}" required />
              @error('billing_month_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="due_date" class="form-label">Due Date</label>
              <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}" />
              @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label for="amount" class="form-label">Base Amount ($) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', '0.00') }}" min="0" required />
              @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label for="discount" class="form-label">Discount ($)</label>
              <input type="number" step="0.01" name="discount" id="discount" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount', '0.00') }}" min="0" />
              @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
              <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="partially_paid" {{ old('status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
              </select>
              @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Generate Invoice</button>
          <a href="{{ route('student-fees.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Auto-populate amount based on selected Fee Type
  document.getElementById('fee_type_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const defaultAmount = selectedOption.getAttribute('data-amount');
    if (defaultAmount) {
      document.getElementById('amount').value = parseFloat(defaultAmount).toFixed(2);
    }
  });
</script>
@endsection