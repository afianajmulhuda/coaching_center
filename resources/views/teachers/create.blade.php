@extends('layouts.master')

@section('title', 'Create Teacher Profile')
@section('page_title', 'Create Teacher Profile')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Link User Account & Setup Teacher Profile</h3>
      </div>

      <form action="{{ route('teachers.store') }}" method="POST">
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
              <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
              <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" placeholder="e.g. Dr. Alex Morgan" required />
              @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="designation" class="form-label">Designation</label>
              <input type="text" name="designation" id="designation" class="form-control @error('designation') is-invalid @enderror" value="{{ old('designation') }}" placeholder="e.g. Senior Lecturer in Physics" />
              @error('designation') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="salary_type" class="form-label">Salary Scheme <span class="text-danger">*</span></label>
              <select name="salary_type" id="salary_type" class="form-select @error('salary_type') is-invalid @enderror" required>
                <option value="monthly" {{ old('salary_type') == 'monthly' ? 'selected' : '' }}>Monthly Salary</option>
                <option value="per_class" {{ old('salary_type') == 'per_class' ? 'selected' : '' }}>Per Class Rate</option>
              </select>
              @error('salary_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="base_salary" class="form-label" id="salary_label">Base Salary ($)</label>
              <input type="number" step="0.01" name="base_salary" id="base_salary" class="form-control @error('base_salary') is-invalid @enderror" value="{{ old('base_salary', '0.00') }}" placeholder="0.00" min="0" />
              @error('base_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Save Profile</button>
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