@extends('layouts.master')

@section('title', 'Edit Batch')
@section('page_title', 'Edit Batch')

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit Batch: {{ $batch->batch_name }}</h3>
      </div>

      <form action="{{ route('batches.update', $batch->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="mb-3">
            <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
            <select name="class_id" id="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
              @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ old('class_id', $batch->class_id) == $class->id ? 'selected' : '' }}>
                  {{ $class->class_name }}
                </option>
              @endforeach
            </select>
            @error('class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label for="batch_name" class="form-label">Batch Name <span class="text-danger">*</span></label>
            <input type="text" name="batch_name" id="batch_name" class="form-control @error('batch_name') is-invalid @enderror" value="{{ old('batch_name', $batch->batch_name) }}" required />
            @error('batch_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label for="max_capacity" class="form-label">Max Capacity</label>
            <input type="number" name="max_capacity" id="max_capacity" class="form-control @error('max_capacity') is-invalid @enderror" value="{{ old('max_capacity', $batch->max_capacity) }}" min="1" />
            @error('max_capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update Batch</button>
          <a href="{{ route('batches.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection