@extends('layouts.master')

@section('title', 'Exams')
@section('page_title', 'Exams & Tests')

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
        <form method="GET" action="{{ route('exams.index') }}" class="row g-3">
          <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search Exam Title..." value="{{ request('search') }}">
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
            <a href="{{ route('exams.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Examination Records</h3>
        <a href="{{ route('exams.create') }}" class="btn btn-primary btn-sm ms-auto">
          <i class="bi bi-plus-lg me-1"></i> Add New Exam
        </a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th style="width: 80px">ID</th>
                <th>Exam Title</th>
                <th>Class</th>
                <th style="width: 150px" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($exams as $exam)
                <tr>
                  <td>{{ $exam->id }}</td>
                  <td><span class="fw-bold">{{ $exam->title }}</span></td>
                  <td><span class="badge text-bg-info">{{ $exam->schoolClass->class_name ?? 'N/A' }}</span></td>
                  <td class="text-end">
                    <a href="{{ route('exams.edit', $exam->id) }}" class="btn btn-sm btn-outline-warning me-1">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('exams.destroy', $exam->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this exam?')">
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
                  <td colspan="4" class="text-center py-4">No exams found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($exams->hasPages())
        <div class="card-footer">
          {{ $exams->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection