@extends('layouts.master')

@section('title', 'Users')

@section('content-header', 'Users')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">All Users</h3>
                <a href="{{ route('users.create') }}" class="btn btn-primary ml-auto">Add User</a>
            </div>
            @if(Session::has('msg'))
                <div class="row p-3">
                    <div class="col-md-6">
                        <h5 class="text-success">{{ Session::get('msg') }}</h5>
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
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key=>$user)
                            
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
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
