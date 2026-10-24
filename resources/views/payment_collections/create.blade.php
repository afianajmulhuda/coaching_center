@extends('layouts.master')

@section('title', 'Collect Payment')
@section('page_title', 'Collect Student Payment')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Record Payment Receipt</h3>
      </div>

      <form action="{{ route('payment-collections.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="invoice_no" class="form-label">Receipt / Invoice No. <span class="text-danger">*</span></label>
              <input type="text" name="invoice_no" id="invoice_no" class="form-control @error('invoice_no') is-invalid @enderror" value="{{ old('invoice_no', $defaultInvoiceNo) }}" required />
              @error('invoice_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="student_fee_id" class="form-label">Pending Student Invoice <span class="text-danger">*</span></label>
              <select name="student_fee_id" id="student_fee_id" class="form-select @error('student_fee_id') is-invalid @enderror" required>
                <option value="">-- Select Pending Fee --</option>
                @foreach($studentFees as $fee)
                  <option value="{{ $fee->id }}" data-net="{{ $fee->net_amount }}" {{ old('student_fee_id', $selectedFeeId) == $fee->id ? 'selected' : '' }}>
                    #INV-{{ str_pad($fee->id, 5, '0', STR_PAD_LEFT) }} - {{ $fee->student->full_name ?? '' }} (${{ number_format($fee->net_amount, 2) }})
                  </option>
                @endforeach
              </select>
              @error('student_fee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="paid_amount" class="form-label">Paid Amount ($) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="paid_amount" id="paid_amount" class="form-control @error('paid_amount') is-invalid @enderror" value="{{ old('paid_amount', '0.00') }}" min="0.01" required />
              @error('paid_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
              <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="bkash" {{ old('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                <option value="nagad" {{ old('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
              </select>
              @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12">
              <label for="transaction_ref" class="form-label">Transaction Reference / TrxID (Optional)</label>
              <input type="text" name="transaction_ref" id="transaction_ref" class="form-control @error('transaction_ref') is-invalid @enderror" value="{{ old('transaction_ref') }}" placeholder="e.g. TRX98234729 or Bank Slip No." />
              @error('transaction_ref') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Save Payment Receipt</button>
          <a href="{{ route('payment-collections.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Auto-set payment amount based on selected invoice net amount
  document.getElementById('student_fee_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const netAmount = selectedOption.getAttribute('data-net');
    if (netAmount) {
      document.getElementById('paid_amount').value = parseFloat(netAmount).toFixed(2);
    }
  });
</script>
@endsection