@extends('layouts.master')

@section('title', 'Create Class')
@section('page_title', 'Create Class')

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Add New Class</h3>
      </div>

      <form action="{{ route('classes.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="mb-3">
            <label for="class_name" class="form-label">Class Name <span class="text-danger">*</span></label>
            <input 
              type="text" 
              name="class_name" 
              id="class_name" 
              class="form-control @error('class_name') is-invalid @enderror" 
              value="{{ old('class_name') }}" 
              placeholder="e.g., Class 10, HSC 1st Year" 
              required 
            />
            @error('class_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Save Class</button>
          <a href="{{ route('classes.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection