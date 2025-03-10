@extends('layouts.master')

@section('title', isset($category) ? 'Edit Category' : 'Add Category')

@section('content-header', isset($category) ? 'Edit Category' : 'Add Category')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ isset($category) ? 'Edit Category' : 'Add Category' }}</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Add Form -->
                <form action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" method="POST">
                    @csrf

                    @isset($category)
                        @method('PUT')
                    @endisset

                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-6 form-group">
                            <label for="Name">Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                class="form-control @error('name') is-invalid @enderror @if(old('name')) is-valid @endif" 
                                value="{{ old('name', $category->name ?? '') }}" 
                               
                            >
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                           
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">
                        {{ isset($category) ? 'Update Category' : 'Add Category' }}
                    </button>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>

@endsection
