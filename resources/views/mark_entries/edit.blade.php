@extends('layouts.master')

@section('title', 'Edit Mark Entry')
@section('page_title', 'Edit Student Mark Entry')

@section('content')
<div class="row">
  <div class="col-md-7">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit Entry #{{ $markEntry->id }}</h3>
      </div>

      <form action="{{ route('mark-entries.update', $markEntry->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label text-muted">Student</label>
            <div><strong>{{ $markEntry->student->name ?? 'N/A' }}</strong> (Roll: {{ $markEntry->student->roll_number ?? 'N/A' }})</div>
          </div>

          <div class="mb-3">
            <label class="form-label text-muted">Exam & Subject</label>
            <div><strong>{{ $markEntry->examSchedule->exam->title ?? '' }}</strong> - {{ $markEntry->examSchedule->subject->subject_name ?? '' }}</div>
            <small class="text-muted">Batch: {{ $markEntry->examSchedule->batch->batch_name ?? '' }} | Max Marks: {{ number_format($markEntry->examSchedule->total_marks ?? 0, 2) }}</small>
          </div>

          <hr>

          <div class="row g-3">
            <div class="col-md-12">
              <div class="form-check form-switch">
                <input type="checkbox" name="is_absent" id="is_absent" value="1" class="form-check-input" {{ old('is_absent', $markEntry->is_absent) ? 'checked' : '' }}>
                <label for="is_absent" class="form-check-label fw-bold">Mark Student as Absent</label>
              </div>
            </div>

            <div class="col-md-12">
              <label for="obtained_marks" class="form-label">Obtained Marks</label>
              <input type="number" step="0.01" name="obtained_marks" id="obtained_marks" class="form-control @error('obtained_marks') is-invalid @enderror" value="{{ old('obtained_marks', $markEntry->obtained_marks) }}" min="0" max="{{ $markEntry->examSchedule->total_marks ?? 100 }}" {{ old('is_absent', $markEntry->is_absent) ? 'disabled' : '' }} />
              @error('obtained_marks') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12">
              <label for="remarks" class="form-label">Remarks</label>
              <input type="text" name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror" value="{{ old('remarks', $markEntry->remarks) }}" placeholder="Optional notes..." />
              @error('remarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update Mark Entry</button>
          <a href="{{ route('mark-entries.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const absentToggle = document.getElementById('is_absent');
    const marksInput = document.getElementById('obtained_marks');

    absentToggle.addEventListener('change', function () {
      if (this.checked) {
        marksInput.value = '';
        marksInput.disabled = true;
      } else {
        marksInput.disabled = false;
      }
    });
  });
</script>
@endsection