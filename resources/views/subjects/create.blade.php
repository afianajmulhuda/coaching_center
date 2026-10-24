@extends('layouts.master')

@section('title', 'Create Subject')
@section('page_title', 'Create Subject')

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Add New Subject</h3>
      </div>

      <form action="{{ route('subjects.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="mb-3">
            <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
            <select name="class_id" id="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
              <option value="">-- Select Class --</option>
              @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                  {{ $class->class_name }}
                </option>
              @endforeach
            </select>
            @error('class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label for="subject_name" class="form-label">Subject Name <span class="text-danger">*</span></label>
            <input type="text" name="subject_name" id="subject_name" class="form-control @error('subject_name') is-invalid @enderror" value="{{ old('subject_name') }}" placeholder="e.g. Higher Mathematics" required />
            @error('subject_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label for="subject_code" class="form-label">Subject Code</label>
            <input type="text" name="subject_code" id="subject_code" class="form-control @error('subject_code') is-invalid @enderror" value="{{ old('subject_code') }}" placeholder="e.g. MATH-101" />
            @error('subject_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Save Subject</button>
          <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection