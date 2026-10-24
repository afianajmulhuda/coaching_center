@extends('layouts.master')

@section('title', 'Edit Role')
@section('page_title', 'Edit Role')

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit Role: {{ $role->name }}</h3>
      </div>
      
      <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="mb-3">
            <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
            <input 
              type="text" 
              name="name" 
              id="name" 
              class="form-control @error('name') is-invalid @enderror" 
              value="{{ old('name', $role->name) }}" 
              required 
            />
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update Role</button>
          <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection