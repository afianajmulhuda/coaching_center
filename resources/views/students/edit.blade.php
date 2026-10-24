@extends('layouts.master')

@section('title', 'Edit Student Record')
@section('page_title', 'Edit Student Record')

@section('content')
<div class="row">
  <div class="col-md-9">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit Record: {{ $student->full_name }}</h3>
      </div>

      <form action="{{ route('students.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Linked Account</label>
              <input type="text" class="form-control" value="{{ $student->user->name ?? 'N/A' }} ({{ $student->user->email ?? '' }})" disabled />
            </div>

            <div class="col-md-6">
              <label for="student_id_no" class="form-label">Student ID No. <span class="text-danger">*</span></label>
              <input type="text" name="student_id_no" id="student_id_no" class="form-control @error('student_id_no') is-invalid @enderror" value="{{ old('student_id_no', $student->student_id_no) }}" required />
              @error('student_id_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="full_name" class="form-label">Student Full Name <span class="text-danger">*</span></label>
              <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $student->full_name) }}" required />
              @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="admission_date" class="form-label">Admission Date <span class="text-danger">*</span></label>
              <input type="date" name="admission_date" id="admission_date" class="form-control @error('admission_date') is-invalid @enderror" value="{{ old('admission_date', $student->admission_date ? $student->admission_date->format('Y-m-d') : '') }}" required />
              @error('admission_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="class_id" class="form-label">Class / Grade <span class="text-danger">*</span></label>
              <select name="class_id" id="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
                @foreach($classes as $class)
                  <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                    {{ $class->class_name }}
                  </option>
                @endforeach
              </select>
              @error('class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="batch_id" class="form-label">Batch <span class="text-danger">*</span></label>
              <select name="batch_id" id="batch_id" class="form-select @error('batch_id') is-invalid @enderror" required>
                @foreach($batches as $batch)
                  <option value="{{ $batch->id }}" {{ old('batch_id', $student->batch_id) == $batch->id ? 'selected' : '' }}>
                    {{ $batch->batch_name }}
                  </option>
                @endforeach
              </select>
              @error('batch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="guardian_name" class="form-label">Guardian Name <span class="text-danger">*</span></label>
              <input type="text" name="guardian_name" id="guardian_name" class="form-control @error('guardian_name') is-invalid @enderror" value="{{ old('guardian_name', $student->guardian_name) }}" required />
              @error('guardian_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="guardian_phone" class="form-label">Guardian Contact Number <span class="text-danger">*</span></label>
              <input type="text" name="guardian_phone" id="guardian_phone" class="form-control @error('guardian_phone') is-invalid @enderror" value="{{ old('guardian_phone', $student->guardian_phone) }}" required />
              @error('guardian_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update Record</button>
          <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection