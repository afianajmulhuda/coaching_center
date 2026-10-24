@extends('layouts.master')

@section('title', 'Students Management')
@section('page_title', 'Student Records')

@section('content')
<div class="row">
  <div class="col-12">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Search/Filters Box -->
    <div class="card mb-3">
      <div class="card-body">
        <form method="GET" action="{{ route('students.index') }}" class="row g-3">
          <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search ID, Name, Guardian..." value="{{ request('search') }}">
          </div>
          <div class="col-md-3">
            <select name="class_id" class="form-select">
              <option value="">-- Filter Class --</option>
              @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                  {{ $class->class_name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select name="batch_id" class="form-select">
              <option value="">-- Filter Batch --</option>
              @foreach($batches as $batch)
                <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                  {{ $batch->batch_name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter"></i> Filter</button>
            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Registered Students</h3>
        <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm ms-auto">
          <i class="bi bi-plus-lg me-1"></i> Add Student
        </a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Class / Batch</th>
                <th>Guardian Info</th>
                <th>Admission Date</th>
                <th style="width: 150px" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($students as $student)
                <tr>
                  <td><code>{{ $student->student_id_no }}</code></td>
                  <td>
                    <span class="fw-bold">{{ $student->full_name }}</span><br>
                    <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $student->user->email ?? 'No User' }}</small>
                  </td>
                  <td>
                    <span class="badge text-bg-primary">{{ $student->class->class_name ?? 'N/A' }}</span>
                    <span class="badge text-bg-secondary">{{ $student->batch->batch_name ?? 'N/A' }}</span>
                  </td>
                  <td>
                    {{ $student->guardian_name }}<br>
                    <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $student->guardian_phone }}</small>
                  </td>
                  <td>{{ \Carbon\Carbon::parse($student->admission_date)->format('M d, Y') }}</td>
                  <td class="text-end">
                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-warning me-1">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this student profile?')">
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
                  <td colspan="6" class="text-center py-4">No student records found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($students->hasPages())
        <div class="card-footer">
          {{ $students->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection