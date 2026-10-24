@extends('layouts.master')

@section('title', 'Create Exam')
@section('page_title', 'Create Exam')

@section('content')
<div class="row">
  <div class="col-md-7">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Add New Exam Record</h3>
      </div>

      <form action="{{ route('exams.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-12">
              <label for="title" class="form-label">Exam Title <span class="text-danger">*</span></label>
              <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g. Weekly Test 1, Term Exam 2026" required />
              @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12">
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
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Save Exam</button>
          <a href="{{ route('exams.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection