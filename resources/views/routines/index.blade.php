@extends('layouts.master')

@section('title', 'Class Routines')
@section('page_title', 'Weekly Class Routines')

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
        <form method="GET" action="{{ route('routines.index') }}" class="row g-3">
          <div class="col-md-4">
            <select name="batch_id" class="form-select">
              <option value="">-- All Batches --</option>
              @foreach($batches as $batch)
                <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                  {{ $batch->class->class_name ?? '' }} - {{ $batch->batch_name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <select name="day_of_week" class="form-select">
              <option value="">-- All Days --</option>
              @foreach($days as $day)
                <option value="{{ $day }}" {{ request('day_of_week') == $day ? 'selected' : '' }}>{{ $day }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter"></i> Filter</button>
            <a href="{{ route('routines.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Class Schedule</h3>
        <a href="{{ route('routines.create') }}" class="btn btn-primary btn-sm ms-auto">
          <i class="bi bi-plus-lg me-1"></i> Add Routine Entry
        </a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th>Day</th>
                <th>Time Slot</th>
                <th>Batch</th>
                <th>Subject</th>
                <th>Teacher</th>
                <th>Room</th>
                <th style="width: 120px" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($routines as $routine)
                <tr>
                  <td><span class="badge text-bg-info">{{ $routine->day_of_week }}</span></td>
                  <td>
                    <i class="bi bi-clock me-1"></i>
                    {{ \Carbon\Carbon::parse($routine->start_time)->format('g:i A') }} - 
                    {{ \Carbon\Carbon::parse($routine->end_time)->format('g:i A') }}
                  </td>
                  <td>
                    <strong>{{ $routine->batch->class->class_name ?? '' }}</strong> 
                    <small class="text-muted">({{ $routine->batch->batch_name ?? 'N/A' }})</small>
                  </td>
                  <td><span class="fw-bold">{{ $routine->subject->subject_name ?? 'N/A' }}</span></td>
                  <td>{{ $routine->teacher->name ?? 'N/A' }}</td>
                  <td><code>{{ $routine->room_number ?? 'N/A' }}</code></td>
                  <td class="text-end">
                    <a href="{{ route('routines.edit', $routine->id) }}" class="btn btn-sm btn-outline-warning me-1">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this schedule entry?')">
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
                  <td colspan="7" class="text-center py-4">No schedules found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($routines->hasPages())
        <div class="card-footer">
          {{ $routines->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection