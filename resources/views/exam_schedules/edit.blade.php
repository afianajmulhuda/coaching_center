@extends('layouts.master')

@section('title', 'Edit Schedule')
@section('page_title', 'Edit Schedule Entry')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit Schedule Entry #{{ $examSchedule->id }}</h3>
      </div>

      <form action="{{ route('exam-schedules.update', $examSchedule->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="exam_id" class="form-label">Exam <span class="text-danger">*</span></label>
              <select name="exam_id" id="exam_id" class="form-select @error('exam_id') is-invalid @enderror" required>
                @foreach($exams as $exam)
                  <option value="{{ $exam->id }}" {{ old('exam_id', $examSchedule->exam_id) == $exam->id ? 'selected' : '' }}>
                    {{ $exam->title }} ({{ $exam->schoolClass->class_name ?? 'Class N/A' }})
                  </option>
                @endforeach
              </select>
              @error('exam_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="batch_id" class="form-label">Batch <span class="text-danger">*</span></label>
              <select name="batch_id" id="batch_id" class="form-select @error('batch_id') is-invalid @enderror" required>
                @foreach($batches as $batch)
                  <option value="{{ $batch->id }}" {{ old('batch_id', $examSchedule->batch_id) == $batch->id ? 'selected' : '' }}>
                    {{ $batch->batch_name }}
                  </option>
                @endforeach
              </select>
              @error('batch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12">
              <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
              <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                @foreach($subjects as $subject)
                  <option value="{{ $subject->id }}" {{ old('subject_id', $examSchedule->subject_id) == $subject->id ? 'selected' : '' }}>
                    {{ $subject->subject_name }}
                  </option>
                @endforeach
              </select>
              @error('subject_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="exam_date" class="form-label">Exam Date <span class="text-danger">*</span></label>
              <input type="date" name="exam_date" id="exam_date" class="form-control @error('exam_date') is-invalid @enderror" value="{{ old('exam_date', $examSchedule->exam_date ? $examSchedule->exam_date->format('Y-m-d') : '') }}" required />
              @error('exam_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="start_time" class="form-label">Start Time</label>
              <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', $examSchedule->start_time ? date('H:i', strtotime($examSchedule->start_time)) : '') }}" />
              @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="total_marks" class="form-label">Total Marks <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="total_marks" id="total_marks" class="form-control @error('total_marks') is-invalid @enderror" value="{{ old('total_marks', $examSchedule->total_marks) }}" min="1" required />
              @error('total_marks') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="pass_marks" class="form-label">Pass Marks <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="pass_marks" id="pass_marks" class="form-control @error('pass_marks') is-invalid @enderror" value="{{ old('pass_marks', $examSchedule->pass_marks) }}" min="0" required />
              @error('pass_marks') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update Schedule</button>
          <a href="{{ route('exam-schedules.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection