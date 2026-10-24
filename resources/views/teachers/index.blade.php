@extends('layouts.master')

@section('title', 'Teachers Management')
@section('page_title', 'Teacher Profiles')

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
        <form method="GET" action="{{ route('teachers.index') }}" class="row g-3">
          <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search Name or Designation..." value="{{ request('search') }}">
          </div>
          <div class="col-md-4">
            <select name="salary_type" class="form-select">
              <option value="">-- All Salary Types --</option>
              <option value="monthly" {{ request('salary_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
              <option value="per_class" {{ request('salary_type') == 'per_class' ? 'selected' : '' }}>Per Class Rate</option>
            </select>
          </div>
          <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter"></i> Filter</button>
            <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Teacher Profiles</h3>
        <a href="{{ route('teachers.create') }}" class="btn btn-primary btn-sm ms-auto">
          <i class="bi bi-plus-lg me-1"></i> Add Teacher Profile
        </a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th style="width: 80px">ID</th>
                <th>Full Name</th>
                <th>User Account</th>
                <th>Designation</th>
                <th>Salary Scheme</th>
                <th>Base Salary / Rate</th>
                <th style="width: 150px" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($teachers as $teacher)
                <tr>
                  <td>{{ $teacher->id }}</td>
                  <td><span class="fw-bold">{{ $teacher->full_name }}</span></td>
                  <td>
                    @if($teacher->user)
                      <span class="badge text-bg-info"><i class="bi bi-person me-1"></i>{{ $teacher->user->email ?? $teacher->user->username }}</span>
                    @else
                      <span class="badge text-bg-danger">Unlinked</span>
                    @endif
                  </td>
                  <td>{{ $teacher->designation ?? 'N/A' }}</td>
                  <td>
                    @if($teacher->salary_type === 'monthly')
                      <span class="badge text-bg-success">Monthly</span>
                    @else
                      <span class="badge text-bg-warning">Per Class</span>
                    @endif
                  </td>
                  <td>
                    <strong>${{ number_format($teacher->base_salary, 2) }}</strong>
                    <small class="text-muted">({{ $teacher->salary_type === 'monthly' ? '/ mo' : '/ class' }})</small>
                  </td>
                  <td class="text-end">
                    <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-sm btn-outline-warning me-1">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this teacher profile?')">
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
                  <td colspan="7" class="text-center py-4">No teacher profiles found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($teachers->hasPages())
        <div class="card-footer">
          {{ $teachers->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection