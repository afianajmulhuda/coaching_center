@extends('layouts.master')

@section('title', 'Result Sheets')
@section('page_title', 'Consolidated Result Sheets')

@section('content')
<div class="row">
  <div class="col-12">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Filters & Actions -->
    <div class="card mb-3">
      <div class="card-body">
        <form method="GET" action="{{ route('result-sheets.index') }}" class="row g-3">
          <div class="col-md-4">
            <select name="exam_id" class="form-select">
              <option value="">-- All Exams --</option>
              @foreach($exams as $exam)
                <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                  {{ $exam->title }} ({{ $exam->schoolClass->class_name ?? '' }})
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
            <input type="text" name="search" class="form-control" placeholder="Search Student Name / Roll..." value="{{ request('search') }}">
          </div>
          <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter"></i> Filter</button>
            <a href="{{ route('result-sheets.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Batch Rankings & Results</h3>
        <div class="d-flex gap-2 ms-auto">
          @if(request('exam_id'))
            <form action="{{ route('result-sheets.toggle-publish') }}" method="POST" class="d-inline">
              @csrf
              <input type="hidden" name="exam_id" value="{{ request('exam_id') }}">
              <input type="hidden" name="action" value="publish">
              <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-eye me-1"></i> Publish All</button>
            </form>
            <form action="{{ route('result-sheets.toggle-publish') }}" method="POST" class="d-inline">
              @csrf
              <input type="hidden" name="exam_id" value="{{ request('exam_id') }}">
              <input type="hidden" name="action" value="unpublish">
              <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye-slash me-1"></i> Unpublish All</button>
            </form>
          @endif
          <a href="{{ route('result-sheets.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-cpu me-1"></i> Generate Results
          </a>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th style="width: 80px" class="text-center">Pos.</th>
                <th>Student</th>
                <th>Exam</th>
                <th>Batch</th>
                <th>Total Obtained</th>
                <th>GPA</th>
                <th>Grade</th>
                <th>Status</th>
                <th style="width: 150px" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($results as $result)
                <tr>
                  <td class="text-center">
                    <span class="badge rounded-pill text-bg-secondary">{{ $result->position_in_batch ?? '-' }}</span>
                  </td>
                  <td>
                    <span class="fw-bold">{{ $result->student->full_name ?? 'N/A' }}</span><br>
                    <small class="text-muted">Roll: {{ $result->student->student_id_no ?? 'N/A' }}</small>
                  </td>
                  <td>
                    <span class="fw-bold">{{ $result->exam->title ?? 'N/A' }}</span><br>
                    <small class="text-muted">{{ $result->exam->ClassModel->class_name ?? '' }}</small>
                  </td>
                  <td><span class="badge text-bg-info">{{ $result->student->batch->batch_name ?? 'N/A' }}</span></td>
                  <td><strong class="fs-6">{{ number_format($result->total_obtained, 2) }}</strong></td>
                  <td><strong>{{ number_format($result->gpa, 2) }}</strong></td>
                  <td>
                    @if($result->grade === 'F')
                      <span class="badge text-bg-danger fs-6">F</span>
                    @else
                      <span class="badge text-bg-success fs-6">{{ $result->grade }}</span>
                    @endif
                  </td>
                  <td>
                    @if($result->is_published)
                      <span class="badge text-bg-success"><i class="bi bi-check-circle me-1"></i> Published</span>
                    @else
                      <span class="badge text-bg-warning"><i class="bi bi-clock me-1"></i> Draft</span>
                    @endif
                  </td>
                  <td class="text-end">
                    <a href="{{ route('result-sheets.show', $result->id) }}" class="btn btn-sm btn-outline-info me-1" title="View Report Card">
                      <i class="bi bi-file-earmark-text"></i>
                    </a>
                    <a href="{{ route('result-sheets.edit', $result->id) }}" class="btn btn-sm btn-outline-warning me-1">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('result-sheets.destroy', $result->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this result sheet?')">
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
                  <td colspan="9" class="text-center py-4">No result sheets found. Select an exam or click "Generate Results".</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($results->hasPages())
        <div class="card-footer">
          {{ $results->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection