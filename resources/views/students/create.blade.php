@extends('layouts.master')

@section('title', 'Register New Student')
@section('page_title', 'Register Student')

@section('content')
<div class="row">
  <div class="col-md-9">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Student Admission Form</h3>
      </div>

      <form action="{{ route('students.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="user_id" class="form-label">User Account <span class="text-danger">*</span></label>
              <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                <option value="">-- Select Account --</option>
                @foreach($availableUsers as $user)
                  <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->email }})
                  </option>
                @endforeach
              </select>
              @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="student_id_no" class="form-label">Student ID No. <span class="text-danger">*</span></label>
              <input type="text" name="student_id_no" id="student_id_no" class="form-control @error('student_id_no') is-invalid @enderror" value="{{ old('student_id_no', $defaultStudentId) }}" required />
              @error('student_id_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="full_name" class="form-label">Student Full Name <span class="text-danger">*</span></label>
              <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" placeholder="John Doe" required />
              @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="admission_date" class="form-label">Admission Date <span class="text-danger">*</span></label>
              <input type="date" name="admission_date" id="admission_date" class="form-control @error('admission_date') is-invalid @enderror" value="{{ old('admission_date', date('Y-m-d')) }}" required />
              @error('admission_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="class_id" class="form-label">Class / Grade <span class="text-danger">*</span></label>
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

            <div class="col-md-6">
              <label for="batch_id" class="form-label">Batch <span class="text-danger">*</span></label>
              <select name="batch_id" id="batch_id" class="form-select @error('batch_id') is-invalid @enderror" required>
                <option value="">-- Select Batch --</option>
                @foreach($batches as $batch)
                  <option value="{{ $batch->id }}" data-class-id="{{ $batch->class_id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                    {{ $batch->batch_name }}
                  </option>
                @endforeach
              </select>
              @error('batch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="guardian_name" class="form-label">Guardian Name <span class="text-danger">*</span></label>
              <input type="text" name="guardian_name" id="guardian_name" class="form-control @error('guardian_name') is-invalid @enderror" value="{{ old('guardian_name') }}" placeholder="Parent/Guardian Name" required />
              @error('guardian_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="guardian_phone" class="form-label">Guardian Contact Number <span class="text-danger">*</span></label>
              <input type="text" name="guardian_phone" id="guardian_phone" class="form-control @error('guardian_phone') is-invalid @enderror" value="{{ old('guardian_phone') }}" placeholder="+123456789" required />
              @error('guardian_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Save Student Record</button>
          <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Filter batches based on selected class
  document.getElementById('class_id').addEventListener('change', function() {
    const classId = this.value;
    const batchSelect = document.getElementById('batch_id');
    const options = batchSelect.querySelectorAll('option');

    options.forEach(option => {
      if (!option.value) return; // Skip default option
      if (option.getAttribute('data-class-id') === classId || !classId) {
        option.style.display = 'block';
      } else {
        option.style.display = 'none';
      }
    });
    batchSelect.value = '';
  });
</script>
@endsection