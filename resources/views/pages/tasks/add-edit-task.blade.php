@extends('layouts.master')

@section('title', isset($task) ? 'Edit Task' : 'Add Task')

@section('content-header', isset($task) ? 'Edit Task' : 'Add Task')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ isset($task) ? 'Edit Task' : 'Add Task' }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}" method="POST">
                    @csrf
                    @if(isset($task))
                        @method('PUT')
                    @endif

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $task->title ?? '') }}" placeholder="Enter Title">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Enter Description">{{ $task->description ?? old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select name="priority" class="form-control @error('priority') is-invalid @enderror">
                            <option value="" selected disabled>Select One Priority</option>
                            <option value="low" {{ (isset($task) && $task->priority === 'low') ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ (isset($task) && $task->priority === 'medium') ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ (isset($task) && $task->priority === 'high') ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="due_date">Due Date</label>
                        <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror"
                               value="{{ $task->due_date ?? old('due_date') }}">
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                            <option value="" selected disabled>Select One Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ (isset($task) && $task->category_id == $category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="assigned_to">Assigned To</label>
                        <select name="assigned_to" class="form-control @error('assigned_to') is-invalid @enderror">
                            <option value="" selected disabled>Select One User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                    {{ (isset($task) && $task->assigned_to == $user->id) ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="pending" {{ (isset($task) && $task->status === 'pending') ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ (isset($task) && $task->status === 'in_progress') ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ (isset($task) && $task->status === 'completed') ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <button type="submit" class="btn btn-success">{{ isset($task) ? 'Update Task' : 'Add Task' }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
