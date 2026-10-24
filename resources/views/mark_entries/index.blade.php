@extends('layouts.master')

@section('title', 'Mark Entries')
@section('page_title', 'Exam Mark Entries')

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
        <form method="GET" action="{{ route('mark-entries.index') }}" class="row g-3">
          <div class="col-md-5">
            <select name="exam_schedule_id" class="form-select">
              <option value="">-- All Exam Schedules --</option>
              @foreach($schedules as $schedule)
                <option value="{{ $schedule->id }}" {{ request('exam_schedule_id') == $schedule->id ? 'selected' : '' }}>
                  {{ $schedule->exam->title ?? 'N/A' }} - {{ $schedule->subject->subject_name ?? '' }} ({{ $schedule->batch->batch_name ?? '' }})
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search Student Name / Roll..." value="{{ request('search') }}">
          </div>
          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter"></i> Filter</button>
            <a href="{{ route('mark-entries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Student Marks Log</h3>
        <a href="{{ route('mark-entries.create') }}" class="btn btn-primary btn-sm ms-auto">
          <i class="bi bi-pencil-square me-1"></i> Bulk Input Marks
        </a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th>Student</th>
                <th>Exam & Subject</th>
                <th>Batch</th>
                <th>Obtained / Max Marks</th>
                <th>Status</th>
                <th>Remarks</th>
                <th style="width: 120px" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($markEntries as $entry)
                @php
                  $passMarks = $entry->examSchedule->pass_marks ?? 0;
                  $totalMarks = $entry->examSchedule->total_marks ?? 0;
                  $isPassed = $entry->obtained_marks >= $passMarks;
                @endphp
                <tr>
                  <td>
                    <span class="fw-bold">{{ $entry->student->full_name ?? 'N/A' }}</span><br>
                    <small class="text-muted">Roll: {{ $entry->student->student_id_no ?? 'N/A' }}</small>
                  </td>
                  <td>
                    <span class="fw-bold">{{ $entry->examSchedule->exam->title ?? 'N/A' }}</span><br>
                    <small class="text-muted">{{ $entry->examSchedule->subject->subject_name ?? 'N/A' }}</small>
                  </td>
                  <td><span class="badge text-bg-info">{{ $entry->examSchedule->batch->batch_name ?? 'N/A' }}</span></td>
                  <td>
                    @if($entry->is_absent)
                      <span class="text-muted">-- / {{ number_format($totalMarks, 2) }}</span>
                    @else
                      <span class="fw-bold">{{ number_format($entry->obtained_marks, 2) }}</span> / {{ number_format($totalMarks, 2) }}
                    @endif
                  </td>
                  <td>
                    @if($entry->is_absent)
                      <span class="badge text-bg-secondary">Absent</span>
                    @elseif($isPassed)
                      <span class="badge text-bg-success">Passed</span>
                    @else
                      <span class="badge text-bg-danger">Failed</span>
                    @endif
                  </td>
                  <td>{{ $entry->remarks ?? '-' }}</td>
                  <td class="text-end">
                    <a href="{{ route('mark-entries.edit', $entry->id) }}" class="btn btn-sm btn-outline-warning me-1">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('mark-entries.destroy', $entry->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this mark entry?')">
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
                  <td colspan="7" class="text-center py-4">No mark entries found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($markEntries->hasPages())
        <div class="card-footer">
          {{ $markEntries->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection