@extends('layouts.master')

@section('title', 'Accounts Ledger')
@section('page_title', 'General Accounts Ledger')

@section('content')
<!-- Financial Summary Cards -->
<div class="row mb-3">
  <div class="col-md-4">
    <div class="card text-bg-success mb-3">
      <div class="card-body">
        <h5 class="card-title"><i class="bi bi-arrow-down-left-circle me-1"></i> Total Income</h5>
        <h3 class="card-text">${{ number_format($totalIncome, 2) }}</h3>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-bg-danger mb-3">
      <div class="card-body">
        <h5 class="card-title"><i class="bi bi-arrow-up-right-circle me-1"></i> Total Expenses</h5>
        <h3 class="card-text">${{ number_format($totalExpense, 2) }}</h3>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-bg-{{ $netBalance >= 0 ? 'primary' : 'warning' }} mb-3">
      <div class="card-body">
        <h5 class="card-title"><i class="bi bi-wallet2 me-1"></i> Net Balance</h5>
        <h3 class="card-text">${{ number_format($netBalance, 2) }}</h3>
      </div>
    </div>
  </div>
</div>

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
        <form method="GET" action="{{ route('account-transactions.index') }}" class="row g-3">
          <div class="col-md-3">
            <select name="transaction_type" class="form-select">
              <option value="">-- All Types --</option>
              <option value="income" {{ request('transaction_type') == 'income' ? 'selected' : '' }}>Income</option>
              <option value="expense" {{ request('transaction_type') == 'expense' ? 'selected' : '' }}>Expense</option>
            </select>
          </div>
          <div class="col-md-3">
            <input type="text" name="category" class="form-control" placeholder="Filter Category (e.g., Rent)..." value="{{ request('category') }}">
          </div>
          <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search Description..." value="{{ request('search') }}">
          </div>
          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter"></i> Filter</button>
            <a href="{{ route('account-transactions.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Transaction Ledger Records</h3>
        <a href="{{ route('account-transactions.create') }}" class="btn btn-primary btn-sm ms-auto">
          <i class="bi bi-plus-lg me-1"></i> New Transaction
        </a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th style="width: 80px">ID</th>
                <th>Type</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Date</th>
                <th style="width: 150px" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($transactions as $txn)
                <tr>
                  <td>{{ $txn->id }}</td>
                  <td>
                    @if($txn->transaction_type === 'income')
                      <span class="badge text-bg-success">Income</span>
                    @else
                      <span class="badge text-bg-danger">Expense</span>
                    @endif
                  </td>
                  <td><span class="fw-bold">{{ $txn->category }}</span></td>
                  <td>
                    <strong class="{{ $txn->transaction_type === 'income' ? 'text-success' : 'text-danger' }}">
                      {{ $txn->transaction_type === 'income' ? '+' : '-' }}${{ number_format($txn->amount, 2) }}
                    </strong>
                  </td>
                  <td><small class="text-muted">{{ $txn->description ?? 'No description provided' }}</small></td>
                  <td>{{ $txn->created_at ? $txn->created_at->format('M d, Y h:i A') : 'N/A' }}</td>
                  <td class="text-end">
                    <a href="{{ route('account-transactions.edit', $txn->id) }}" class="btn btn-sm btn-outline-warning me-1">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('account-transactions.destroy', $txn->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this transaction entry?')">
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
                  <td colspan="7" class="text-center py-4">No account transactions recorded yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($transactions->hasPages())
        <div class="card-footer">
          {{ $transactions->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection