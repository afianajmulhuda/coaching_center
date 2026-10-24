@extends('layouts.master')

@section('title', 'Roles Management')
@section('page_title', 'Roles')

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

    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All System Roles</h3>
        <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm ms-auto">
          <i class="bi bi-plus-lg me-1"></i> Add New Role
        </a>
      </div>
      
      <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
          <thead>
            <tr>
              <th style="width: 80px">ID</th>
              <th>Role Name</th>
              <th>Assigned Users</th>
              <th style="width: 150px" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($roles as $role)
              <tr>
                <td>{{ $role->id }}</td>
                <td><span class="fw-bold">{{ $role->name }}</span></td>
                <td>
                  <span class="badge text-bg-info">{{ $role->users_count }} Users</span>
                </td>
                <td class="text-end">
                  <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-warning me-1">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this role?')">
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
                <td colspan="4" class="text-center py-3">No roles found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
@endsection