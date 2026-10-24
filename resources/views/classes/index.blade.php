@extends('layouts.master')

@section('title', 'Classes Management')
@section('page_title', 'Classes')

@section('content')
<div class="row">
  <div class="col-12">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Filter/Search Box -->
    <div class="card mb-3">
      <div class="card-body">
        <form method="GET" action="{{ route('classes.index') }}" class="row g-3">
          <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search Class Name..." value="{{ request('search') }}">
          </div>
          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-search"></i> Search</button>
            <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Classes / Grades</h3>
        <a href="{{ route('classes.create') }}" class="btn btn-primary btn-sm ms-auto">
          <i class="bi bi-plus-lg me-1"></i> Add New Class
        </a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th style="width: 80px">ID</th>
                <th>Class Name</th>
                <th style="width: 150px" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($classes as $class)
                <tr>
                  <td>{{ $class->id }}</td>
                  <td><span class="fw-bold">{{ $class->class_name }}</span></td>
                  <td class="text-end">
                    <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-sm btn-outline-warning me-1">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('classes.destroy', $class->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this class?')">
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
                  <td colspan="3" class="text-center py-4">No classes found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($classes->hasPages())
        <div class="card-footer">
          {{ $classes->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection