@extends('layouts.master')

@section('title', isset($user) ? 'Edit User' : 'Add User')

@section('content-header', isset($user) ? 'Edit User' : 'Add User')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ isset($user) ? 'Edit User' : 'Add User' }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
                    @csrf
                    @if(isset($user))
                        @method('PUT')
                    @endif

                    <!-- Name Field -->
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $user->name ?? '') }}" placeholder="Enter Name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email ?? '') }}" placeholder="Enter Email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                   

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Enter Password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="Confirm Password">
                               @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                    </div>

                     <!-- Role Field -->
                     <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" class="form-control @error('role') is-invalid @enderror">
                            <option value="" disabled selected>Select Role</option>
                            @if(auth()->user()->role == 'admin')
                                <option value="manager" {{ old('role', $user->role ?? '') == 'manager' ? 'selected' : '' }}>Manager</option>
                            @endif
                            <option value="user" {{ old('role', $user->role ?? '') == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success">
                        {{ isset($user) ? 'Update User' : 'Add User' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
