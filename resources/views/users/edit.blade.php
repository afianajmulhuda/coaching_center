@extends('layouts.master')

@section('title', 'Edit User')
@section('page_title', 'Edit User')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit User: {{ $user->username }}</h3>
      </div>

      <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
              <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required />
              @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
              <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" required />
              @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" />
              @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="password" class="form-label">Password <small class="text-muted">(Leave empty to keep existing)</small></label>
              <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="New Password" />
              @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
              <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                @foreach($roles as $role)
                  <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                  </option>
                @endforeach
              </select>
              @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label for="status" class="form-label">Account Status <span class="text-danger">*</span></label>
              <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
              </select>
              @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update Account</button>
          <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection