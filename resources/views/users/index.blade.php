@extends('layouts.master')

@section('title', 'Users Management')
@section('page_title', 'Users')

@section('content')
<div class="row">
  <div class="col-12">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Filters Section -->
    <div class="card mb-3">
      <div class="card-body">
        <form method="GET" action="{{ route('users.index') }}" class="row g-3">
          <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search Username, Email, Phone..." value="{{ request('search') }}">
          </div>
          <div class="col-md-3">
            <select name="role_id" class="form-select">
              <option value="">-- All Roles --</option>
              @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select name="status" class="form-select">
              <option value="">-- All Status --</option>
              <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
              <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
          </div>
          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter"></i> Filter</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">User Accounts</h3>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm ms-auto">
          <i class="bi bi-person-plus me-1"></i> Add New User
        </a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th style="width: 60px">ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Created At</th>
                <th style="width: 130px" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $user)
                <tr>
                  <td>{{ $user->id }}</td>
                  <td><span class="fw-bold">{{ $user->username }}</span></td>
                  <td>{{ $user->email ?? 'N/A' }}</td>
                  <td>{{ $user->phone }}</td>
                  <td>
                    <span class="badge text-bg-info">{{ $user->role->name ?? 'Unassigned' }}</span>
                  </td>
                  <td>
                    @if($user->status === 'active')
                      <span class="badge bg-success">Active</span>
                    @else
                      <span class="badge bg-secondary">Inactive</span>
                    @endif
                  </td>
                  <td>{{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                  <td class="text-end">
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning me-1">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
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
                  <td colspan="8" class="text-center py-4">No users found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($users->hasPages())
        <div class="card-footer">
          {{ $users->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection