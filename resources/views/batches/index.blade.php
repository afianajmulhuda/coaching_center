@extends('layouts.master')

@section('title', 'Batches Management')
@section('page_title', 'Batches')

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
        <form method="GET" action="{{ route('batches.index') }}" class="row g-3">
          <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search Batch Name..." value="{{ request('search') }}">
          </div>
          <div class="col-md-4">
            <select name="class_id" class="form-select">
              <option value="">-- All Classes --</option>
              @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                  {{ $class->class_name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter"></i> Filter</button>
            <a href="{{ route('batches.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Batches</h3>
        <a href="{{ route('batches.create') }}" class="btn btn-primary btn-sm ms-auto">
          <i class="bi bi-plus-lg me-1"></i> Add New Batch
        </a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th style="width: 80px">ID</th>
                <th>Batch Name</th>
                <th>Assigned Class</th>
                <th>Max Capacity</th>
                <th style="width: 150px" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($batches as $batch)
                <tr>
                  <td>{{ $batch->id }}</td>
                  <td><span class="fw-bold">{{ $batch->batch_name }}</span></td>
                  <td>
                    <span class="badge text-bg-secondary">{{ $batch->class->class_name ?? 'N/A' }}</span>
                  </td>
                  <td>{{ $batch->max_capacity }} Students</td>
                  <td class="text-end">
                    <a href="{{ route('batches.edit', $batch->id) }}" class="btn btn-sm btn-outline-warning me-1">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('batches.destroy', $batch->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this batch?')">
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
                  <td colspan="5" class="text-center py-4">No batches found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($batches->hasPages())
        <div class="card-footer">
          {{ $batches->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection