@extends('layouts.master')

@section('title', 'Edit Result Sheet')
@section('page_title', 'Edit Result Entry')

@section('content')
<div class="row">
  <div class="col-md-7">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Override Result Entry #{{ $resultSheet->id }}</h3>
      </div>

      <form action="{{ route('result-sheets.update', $resultSheet->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label text-muted">Student</label>
            <div><strong>{{ $resultSheet->student->name ?? 'N/A' }}</strong> (Roll: {{ $resultSheet->student->roll_number ?? 'N/A' }})</div>
          </div>

          <div class="mb-3">
            <label class="form-label text-muted">Exam</label>
            <div><strong>{{ $resultSheet->exam->title ?? 'N/A' }}</strong></div>
          </div>

          <hr>

          <div class="row g-3">
            <div class="col-md-6">
              <label for="total_obtained" class="form-label">Total Obtained Marks <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="total_obtained" id="total_obtained" class="form-control @error('total_obtained') is-invalid @enderror" value="{{ old('total_obtained', $resultSheet->total_obtained) }}" min="0" required />
              @error('total_obtained') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="gpa" class="form-label">GPA</label>
              <input type="number" step="0.01" name="gpa" id="gpa" class="form-control @error('gpa') is-invalid @enderror" value="{{ old('gpa', $resultSheet->gpa) }}" min="0" max="5.00" />
              @error('gpa') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="grade" class="form-label">Grade</label>
              <input type="text" name="grade" id="grade" class="form-control @error('grade') is-invalid @enderror" value="{{ old('grade', $resultSheet->grade) }}" placeholder="e.g. A+, A, B, F" />
              @error('grade') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="position_in_batch" class="form-label">Batch Position</label>
              <input type="number" name="position_in_batch" id="position_in_batch" class="form-control @error('position_in_batch') is-invalid @enderror" value="{{ old('position_in_batch', $resultSheet->position_in_batch) }}" min="1" />
              @error('position_in_batch') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12">
              <div class="form-check form-switch mt-2">
                <input type="checkbox" name="is_published" id="is_published" value="1" class="form-check-input" {{ old('is_published', $resultSheet->is_published) ? 'checked' : '' }}>
                <label for="is_published" class="form-check-label fw-bold">Publish Result</label>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update Result Sheet</button>
          <a href="{{ route('result-sheets.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection