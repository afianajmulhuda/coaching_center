@extends('layouts.master')

@section('title', 'Edit Class')
@section('page_title', 'Edit Class')

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit Class: {{ $class->class_name }}</h3>
      </div>

      <form action="{{ route('classes.update', $class->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="mb-3">
            <label for="class_name" class="form-label">Class Name <span class="text-danger">*</span></label>
            <input 
              type="text" 
              name="class_name" 
              id="class_name" 
              class="form-control @error('class_name') is-invalid @enderror" 
              value="{{ old('class_name', $class->class_name) }}" 
              required 
            />
            @error('class_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update Class</button>
          <a href="{{ route('classes.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection