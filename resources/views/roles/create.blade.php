@extends('layouts.master')

@section('title', 'Create Role')
@section('page_title', 'Create Role')

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Add New Role</h3>
      </div>
      
      <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="mb-3">
            <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
            <input 
              type="text" 
              name="name" 
              id="name" 
              class="form-control @error('name') is-invalid @enderror" 
              value="{{ old('name') }}" 
              placeholder="e.g. Admin, Teacher, Student" 
              required 
            />
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Save Role</button>
          <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection