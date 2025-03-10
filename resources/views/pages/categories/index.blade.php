@extends('layouts.master')

@section('title', 'Categories')

@section('content-header', 'Categories')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">All Categories</h3>
                <a href="{{ route('categories.create') }}" class="btn btn-primary ml-auto">Add Category</a>
            </div>
            @if(Session::has('success'))
                <div class="row p-3">
                    <div class="col-md-6">
                        <h5 class="text-success">{{ Session::get('success') }}</h5>
                    </div>
                </div>
            @endif
            <div class="card-body">
                <!-- Products Table -->
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sr.no</th>
                            <th>Name</th>
                            <th>Actions</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $key=>$category)
                            
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $category->name }}</td>
                               
                                <td>
                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection
