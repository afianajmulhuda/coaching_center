@extends('layouts.master')

@section('title', 'Generate Results')
@section('page_title', 'Calculate Exam Results')

@section('content')
<div class="row">
  <div class="col-md-7">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Run Automated Result Engine</h3>
      </div>

      <form action="{{ route('result-sheets.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <p class="text-muted">Select an Exam and Batch to aggregate marks from all subject schedules, compute GPA/grades, and calculate student positions.</p>

          <div class="row g-3">
            <div class="col-md-12">
              <label for="exam_id" class="form-label">Target Exam <span class="text-danger">*</span></label>
              <select name="exam_id" id="exam_id" class="form-select @error('exam_id') is-invalid @enderror" required>
                <option value="">-- Select Exam --</option>
                @foreach($exams as $exam)
                  <option value="{{ $exam->id }}" {{ old('exam_id') == $exam->id ? 'selected' : '' }}>
                    {{ $exam->title }} ({{ $exam->schoolClass->class_name ?? 'Class N/A' }})
                  </option>
                @endforeach
              </select>
              @error('exam_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12">
              <label for="batch_id" class="form-label">Target Batch <span class="text-danger">*</span></label>
              <select name="batch_id" id="batch_id" class="form-select @error('batch_id') is-invalid @enderror" required>
                <option value="">-- Select Batch --</option>
                @foreach($batches as $batch)
                  <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                    {{ $batch->batch_name }}
                  </option>
                @endforeach
              </select>
              @error('batch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary"><i class="bi bi-gear-wide-connected me-1"></i> Calculate & Rank Results</button>
          <a href="{{ route('result-sheets.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection