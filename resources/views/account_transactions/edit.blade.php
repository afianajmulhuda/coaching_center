@extends('layouts.master')

@section('title', 'Edit Transaction')
@section('page_title', 'Edit Transaction Entry')

@section('content')
<div class="row">
  <div class="col-md-7">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit Transaction #{{ $accountTransaction->id }}</h3>
      </div>

      <form action="{{ route('account-transactions.update', $accountTransaction->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="transaction_type" class="form-label">Transaction Type <span class="text-danger">*</span></label>
              <select name="transaction_type" id="transaction_type" class="form-select @error('transaction_type') is-invalid @enderror" required>
                <option value="income" {{ old('transaction_type', $accountTransaction->transaction_type) == 'income' ? 'selected' : '' }}>Income (+)</option>
                <option value="expense" {{ old('transaction_type', $accountTransaction->transaction_type) == 'expense' ? 'selected' : '' }}>Expense (-)</option>
              </select>
              @error('transaction_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
              <input type="text" name="category" id="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category', $accountTransaction->category) }}" required />
              @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12">
              <label for="amount" class="form-label">Amount ($) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $accountTransaction->amount) }}" min="0.01" required />
              @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12">
              <label for="description" class="form-label">Description / Remarks</label>
              <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $accountTransaction->description) }}</textarea>
              @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update Transaction</button>
          <a href="{{ route('account-transactions.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection