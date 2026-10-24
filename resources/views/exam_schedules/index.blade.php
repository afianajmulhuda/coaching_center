@extends('layouts.master')

@section('title', 'Exam Schedules')
@section('page_title', 'Exam Schedules & Marks Setup')

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
        <form method="GET" action="{{ route('exam-schedules.index') }}" class="row g-3">
          <div class="col-md-3">
            <select name="exam_id" class="form-select">
              <option value="">-- All Exams --</option>
              @foreach($exams as $exam)
                <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                  {{ $exam->title }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select name="batch_id" class="form-select">
              <option value="">-- All Batches --</option>
              @foreach($batches as $batch)
                <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                  {{ $batch->batch_name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select name="subject_id" class="form-select">
              <option value="">-- All Subjects --</option>
              @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                  {{ $subject->subject_name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter"></i> Filter</button>
            <a href="{{ route('exam-schedules.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Exam Timetable & Subject Criteria</h3>
        <a href="{{ route('exam-schedules.create') }}" class="btn btn-primary btn-sm ms-auto">
          <i class="bi bi-plus-lg me-1"></i> Add Schedule
        </a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th>Exam</th>
                <th>Subject</th>
                <th>Batch</th>
                <th>Date & Time</th>
                <th>Total Marks</th>
                <th>Pass Marks</th>
                <th style="width: 150px" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($schedules as $schedule)
                <tr>
                  <td>
                    <span class="fw-bold">{{ $schedule->exam->title ?? 'N/A' }}</span><br>
                    <small class="text-muted">{{ $schedule->exam->schoolClass->class_name ?? '' }}</small>
                  </td>
                  <td><span class="badge text-bg-primary">{{ $schedule->subject->subject_name ?? 'N/A' }}</span></td>
                  <td><span class="badge text-bg-info">{{ $schedule->batch->batch_name ?? 'N/A' }}</span></td>
                  <td>
                    <i class="bi bi-calendar-event me-1"></i>
                    {{ $schedule->exam_date ? date('M d, Y', strtotime($schedule->exam_date)) : 'N/A' }} <br>
                    <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $schedule->start_time ? date('h:i A', strtotime($schedule->start_time)) : 'TBA' }}</small>
                  </td>
                  <td><strong>{{ number_format($schedule->total_marks, 2) }}</strong></td>
                  <td><span class="text-danger fw-bold">{{ number_format($schedule->pass_marks, 2) }}</span></td>
                  <td class="text-end">
                    <a href="{{ route('exam-schedules.edit', $schedule->id) }}" class="btn btn-sm btn-outline-warning me-1">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('exam-schedules.destroy', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this schedule?')">
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
                  <td colspan="7" class="text-center py-4">No exam schedules found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($schedules->hasPages())
        <div class="card-footer">
          {{ $schedules->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection