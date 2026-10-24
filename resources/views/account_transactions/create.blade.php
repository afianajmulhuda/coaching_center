@extends('layouts.master')

@section('title', 'New Transaction')
@section('page_title', 'Record Transaction')

@section('content')
<div class="row">
  <div class="col-md-7">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Add Income or Expense Record</h3>
      </div>

      <form action="{{ route('account-transactions.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="transaction_type" class="form-label">Transaction Type <span class="text-danger">*</span></label>
              <select name="transaction_type" id="transaction_type" class="form-select @error('transaction_type') is-invalid @enderror" required>
                <option value="income" {{ old('transaction_type') == 'income' ? 'selected' : '' }}>Income (+)</option>
                <option value="expense" {{ old('transaction_type') == 'expense' ? 'selected' : '' }}>Expense (-)</option>
              </select>
              @error('transaction_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
              <input type="text" name="category" id="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category') }}" placeholder="e.g. Rent, Electricity, Teacher Salary" required list="category-suggestions" />
              <datalist id="category-suggestions">
                <option value="Rent">
                <option value="Electricity">
                <option value="Teacher Salary">
                <option value="Tuition Collection">
                <option value="Internet / Utility">
                <option value="Office Supplies">
              </datalist>
              @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12">
              <label for="amount" class="form-label">Amount ($) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', '0.00') }}" min="0.01" required />
              @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12">
              <label for="description" class="form-label">Description / Remarks</label>
              <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Details about this transaction...">{{ old('description') }}</textarea>
              @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Save Transaction</button>
          <a href="{{ route('account-transactions.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection