@extends('layouts.master')

@section('title', 'Edit Payment Collection')
@section('page_title', 'Edit Payment Collection')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit Receipt {{ $paymentCollection->invoice_no }}</h3>
      </div>

      <form action="{{ route('payment-collections.update', $paymentCollection->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="invoice_no" class="form-label">Receipt / Invoice No. <span class="text-danger">*</span></label>
              <input type="text" name="invoice_no" id="invoice_no" class="form-control @error('invoice_no') is-invalid @enderror" value="{{ old('invoice_no', $paymentCollection->invoice_no) }}" required />
              @error('invoice_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="student_fee_id" class="form-label">Student Invoice <span class="text-danger">*</span></label>
              <select name="student_fee_id" id="student_fee_id" class="form-select @error('student_fee_id') is-invalid @enderror" required>
                @foreach($studentFees as $fee)
                  <option value="{{ $fee->id }}" {{ old('student_fee_id', $paymentCollection->student_fee_id) == $fee->id ? 'selected' : '' }}>
                    #INV-{{ str_pad($fee->id, 5, '0', STR_PAD_LEFT) }} - {{ $fee->student->full_name ?? '' }}
                  </option>
                @endforeach
              </select>
              @error('student_fee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="paid_amount" class="form-label">Paid Amount ($) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="paid_amount" id="paid_amount" class="form-control @error('paid_amount') is-invalid @enderror" value="{{ old('paid_amount', $paymentCollection->paid_amount) }}" min="0.01" required />
              @error('paid_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
              <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                <option value="cash" {{ old('payment_method', $paymentCollection->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="bkash" {{ old('payment_method', $paymentCollection->payment_method) == 'bkash' ? 'selected' : '' }}>bKash</option>
                <option value="nagad" {{ old('payment_method', $paymentCollection->payment_method) == 'nagad' ? 'selected' : '' }}>Nagad</option>
                <option value="bank" {{ old('payment_method', $paymentCollection->payment_method) == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
              </select>
              @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12">
              <label for="transaction_ref" class="form-label">Transaction Reference / TrxID (Optional)</label>
              <input type="text" name="transaction_ref" id="transaction_ref" class="form-control @error('transaction_ref') is-invalid @enderror" value="{{ old('transaction_ref', $paymentCollection->transaction_ref) }}" />
              @error('transaction_ref') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update Payment Record</button>
          <a href="{{ route('payment-collections.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection