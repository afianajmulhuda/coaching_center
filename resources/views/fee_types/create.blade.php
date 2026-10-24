@extends('layouts.master')

@section('title', 'Create Fee Type')
@section('page_title', 'Create Fee Type')

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Add New Fee Type</h3>
      </div>

      <form action="{{ route('fee-types.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="mb-3">
            <label for="title" class="form-label">Fee Title <span class="text-danger">*</span></label>
            <input 
              type="text" 
              name="title" 
              id="title" 
              class="form-control @error('title') is-invalid @enderror" 
              value="{{ old('title') }}" 
              placeholder="e.g., Monthly Tuition, Admission Fee" 
              required 
            />
            @error('title')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="default_amount" class="form-label">Default Amount ($) <span class="text-danger">*</span></label>
            <input 
              type="number" 
              step="0.01" 
              name="default_amount" 
              id="default_amount" 
              class="form-control @error('default_amount') is-invalid @enderror" 
              value="{{ old('default_amount', '0.00') }}" 
              min="0" 
              required 
            />
            @error('default_amount')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Save Fee Type</button>
          <a href="{{ route('fee-types.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection