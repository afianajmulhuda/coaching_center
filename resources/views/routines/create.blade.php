@extends('layouts.master')

@section('title', 'Add Routine Entry')
@section('page_title', 'Add Routine Entry')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Schedule New Class Slot</h3>
      </div>

      <form action="{{ route('routines.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="batch_id" class="form-label">Batch <span class="text-danger">*</span></label>
              <select name="batch_id" id="batch_id" class="form-select @error('batch_id') is-invalid @enderror" required>
                <option value="">-- Select Batch --</option>
                @foreach($batches as $batch)
                  <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                    {{ $batch->class->class_name ?? '' }} - {{ $batch->batch_name }}
                  </option>
                @endforeach
              </select>
              @error('batch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
              <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                <option value="">-- Select Subject --</option>
                @foreach($subjects as $subject)
                  <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                    {{ $subject->subject_name }} ({{ $subject->subject_code ?? 'No Code' }})
                  </option>
                @endforeach
              </select>
              @error('subject_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="teacher_id" class="form-label">Teacher <span class="text-danger">*</span></label>
              <select name="teacher_id" id="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                <option value="">-- Select Teacher --</option>
                @foreach($teachers as $teacher)
                  <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                    {{ $teacher->name }}
                  </option>
                @endforeach
              </select>
              @error('teacher_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="day_of_week" class="form-label">Day of Week <span class="text-danger">*</span></label>
              <select name="day_of_week" id="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror" required>
                <option value="">-- Select Day --</option>
                @foreach($days as $day)
                  <option value="{{ $day }}" {{ old('day_of_week') == $day ? 'selected' : '' }}>{{ $day }}</option>
                @endforeach
              </select>
              @error('day_of_week') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
              <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required />
              @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
              <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required />
              @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label for="room_number" class="form-label">Room Number</label>
              <input type="text" name="room_number" id="room_number" class="form-control @error('room_number') is-invalid @enderror" value="{{ old('room_number') }}" placeholder="e.g. Room 301" />
              @error('room_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Save Schedule</button>
          <a href="{{ route('routines.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection