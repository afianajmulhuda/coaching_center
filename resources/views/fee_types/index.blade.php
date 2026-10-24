@extends('layouts.master')

@section('title', 'Fee Types')
@section('page_title', 'Fee Types')

@section('content')
<div class="row">
  <div class="col-12">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Search Form -->
    <div class="card mb-3">
      <div class="card-body">
        <form method="GET" action="{{ route('fee-types.index') }}" class="row g-3">
          <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search Fee Title..." value="{{ request('search') }}">
          </div>
          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-search"></i> Search</button>
            <a href="{{ route('fee-types.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Configured Fee Types</h3>
        <a href="{{ route('fee-types.create') }}" class="btn btn-primary btn-sm ms-auto">
          <i class="bi bi-plus-lg me-1"></i> Add Fee Type
        </a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th style="width: 80px">ID</th>
                <th>Fee Title</th>
                <th>Default Amount</th>
                <th style="width: 150px" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($feeTypes as $type)
                <tr>
                  <td>{{ $type->id }}</td>
                  <td><span class="fw-bold">{{ $type->title }}</span></td>
                  <td><span class="badge text-bg-success font-monospace">${{ number_format($type->default_amount, 2) }}</span></td>
                  <td class="text-end">
                    <a href="{{ route('fee-types.edit', $type->id) }}" class="btn btn-sm btn-outline-warning me-1">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('fee-types.destroy', $type->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this fee type?')">
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
                  <td colspan="4" class="text-center py-4">No fee types configured yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($feeTypes->hasPages())
        <div class="card-footer">
          {{ $feeTypes->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection