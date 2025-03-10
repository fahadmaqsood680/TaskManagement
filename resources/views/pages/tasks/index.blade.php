@extends('layouts.master')

@section('title', 'Tasks')

@section('content-header', 'Tasks')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">All Tasks</h3>
                <a href="{{ route('tasks.create') }}" class="btn btn-primary ml-auto">Add Task</a>
            </div>

            @if(Session::has('success'))
                <div class="row p-3">
                    <div class="col-md-6">
                        <h5 class="alert alert-success">{{ Session::get('success') }}</h5>
                    </div>
                </div>
            @endif

            <div class="card-body">
                <!-- Search Filters -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="searchTitle" class="form-control" placeholder="Search by Title">
                    </div>
                    <div class="col-md-6">
                        <select id="searchPriority" class="form-control">
                            <option value="">-- Select Priority --</option>
                            <option value="low">low</option>
                            <option value="medium">medium</option>
                            <option value="high">high</option>
                        </select>
                    </div>
                   
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                            <input type="date" id="searchDueDate" class="form-control" placeholder="Search by Due Date">
                    </div>
                    
                </div>

                <div class="row mt-3 mb-4">
                    <div class="col-md-6">
                        <button id="filterBtn" class="btn btn-primary">Filter</button>
                        <button id="resetBtn" class="btn btn-secondary ml-2">Reset</button>
                    </div>
                </div>

                <!-- Tasks Table with DataTables -->
                <table class="table table-bordered table-striped" id="myTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Due Date</th>
                            <th>Assigned By</th>
                            <th>Assigned To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let table = $('#myTable').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        ajax: {
            url: "{{ route('tasks.index') }}",
            data: function(d) {
                d.title = $('#searchTitle').val();
                d.priority = $('#searchPriority').val();
                d.due_date = $('#searchDueDate').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'title', name: 'title' },
            { data: 'priority', name: 'priority' },
            { data: 'due_date', name: 'due_date' },
            { 
                data: 'assigned_by', 
                name: 'assigned_by', 
                render: function(data, type, row) {
                    return row.assigned_by ? row.assigned_by.name : 'N/A';
                }
            },
            { 
                data: 'assigned_to', 
                name: 'assigned_to',
                render: function(data, type, row) {
                    return row.assigned_to ? row.assigned_to.name : 'N/A';
                }
            },
            {
                data: 'id',
                render: function(data, type, row) {
                    return `
                        <a href="/tasks/${data}/edit" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="/tasks/${data}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    `;
                },
                orderable: false,
                searchable: false
            }
        ],
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50, 100]
    });

    $('#filterBtn').on('click', function() {
        table.draw();  
    });

    $('#resetBtn').on('click', function() {
        $('#searchTitle').val('');
        $('#searchPriority').val('');
        $('#searchDueDate').val('');
        table.draw(); 
    });
});
</script>
@endpush
