@extends('layouts.master')

@section('title', 'Edit Teacher Profile')
@section('page_title', 'Edit Teacher Profile')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit Profile: {{ $teacher->full_name }}</h3>
      </div>

      <form action="{{ route('teachers.update', $teacher->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Linked Account</label>
              <input type="text" class="form-control" value="{{ $teacher->user->name ?? 'N/A' }} ({{ $teacher->user->email ?? 'No email' }})" disabled />
            </div>

            <div class="col-md-6">
              <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
              <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $teacher->full_name) }}" required />
              @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="designation" class="form-label">Designation</label>
              <input type="text" name="designation" id="designation" class="form-control @error('designation') is-invalid @enderror" value="{{ old('designation', $teacher->designation) }}" />
              @error('designation') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="salary_type" class="form-label">Salary Scheme <span class="text-danger">*</span></label>
              <select name="salary_type" id="salary_type" class="form-select @error('salary_type') is-invalid @enderror" required>
                <option value="monthly" {{ old('salary_type', $teacher->salary_type) == 'monthly' ? 'selected' : '' }}>Monthly Salary</option>
                <option value="per_class" {{ old('salary_type', $teacher->salary_type) == 'per_class' ? 'selected' : '' }}>Per Class Rate</option>
              </select>
              @error('salary_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="base_salary" class="form-label" id="salary_label">
                {{ $teacher->salary_type === 'per_class' ? 'Rate Per Class ($)' : 'Base Salary ($)' }}
              </label>
              <input type="number" step="0.01" name="base_salary" id="base_salary" class="form-control @error('base_salary') is-invalid @enderror" value="{{ old('base_salary', $teacher->base_salary) }}" min="0" />
              @error('base_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update Profile</button>
          <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.getElementById('salary_type').addEventListener('change', function() {
    const label = document.getElementById('salary_label');
    label.innerText = this.value === 'per_class' ? 'Rate Per Class ($)' : 'Base Salary ($)';
  });
</script>
@endsection