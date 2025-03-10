@extends('layouts.master')
@section('title','Task Dashboard')

@section('content')

<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3 id="completed-tasks-count">{{ $completedTasks }}</h3>
                <p>Completed Tasks</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="pending-tasks-count">{{ $pendingTasks }}</h3>
                <p>Pending Tasks</p>
            </div>
            <div class="icon">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="in-progress-tasks-count">{{ $inProgressTasks }}</h3>
                <p>In Progress Tasks</p>
            </div>
            <div class="icon">
                <i class="fas fa-spinner"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <!-- Calendar Section -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Task Calendar</div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Task Progress Section -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Task Progress</div>
            <div class="card-body" id="task-progress-section">
                <!-- Progress bars will be dynamically added here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #calendar {
        background-color: #f0f2f5; /* Light grey background */
        border-radius: 10px;
        padding: 15px;
    }

    .fc-day {
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
    }

    .fc-today {
        background-color: #d4edda !important;
    }

    .fc-event {
        background-color: #007bff;
        border: none;
        color: #fff;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    updateTaskCounts();
    setInterval(updateTaskCounts, 10000); 
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#calendar').fullCalendar({
        defaultView: 'month',
        events: function(start, end, timezone, callback) {
            $.ajax({
                url: "{{ route('tasks.getCalendarTasks') }}",
                dataType: 'json',
                success: function(data) {
                    const events = data.map(task => ({
                        id: task.id,
                        title: task.title,
                        start: task.start,
                        status: task.status,
                        comments: task.comments,
                        progress: task.progress,
                        assign_user: task.assigned_user
                    }));
                    callback(events);
                    renderProgressBars(data);
                }
            });
        },

        eventClick: function(event) {
            let commentsHtml = '';

            if (event.status === 'completed') {
                commentsHtml = event.comments.map(comment => `
                    <div class="mt-2">
                        <strong>${comment.user}</strong>: ${comment.content}
                        <small class="text-muted">${comment.created_at}</small>
                    </div>
                `).join('') || '<p>No comments yet.</p>';

                commentsHtml += `
                    <textarea id="new-comment" class="form-control mt-2" placeholder="Add a comment..." required></textarea>
                `;
            }

            Swal.fire({
                title: event.title,
                html: `
                   
                    <p><strong>Status:</strong> ${event.status}</p>
                    <p><strong>Assigned To:</strong> ${event.assign_user}</p>
                    ${commentsHtml}
                `,
                showCancelButton: event.status === 'completed',
                confirmButtonText: event.status === 'completed' ? 'Add Comment' : 'OK',
                preConfirm: () => {
                    if (event.status === 'completed') {
                        const newComment = $('#new-comment').val().trim();
                        if (!newComment) {
                            Swal.showValidationMessage('Comment is required');
                            return false;  // Prevent closing the modal
                        }

                        return $.ajax({
                            url: `/tasks/${event.id}/comments`,
                            method: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                content: newComment
                            }
                        }).then(response => response)
                        .catch(() => {
                            Swal.showValidationMessage('Failed to add comment.');
                        });
                    }
                }
            }).then((result) => {
                if (result.isConfirmed && result.value?.success) {
                    Swal.fire('Comment Added!', '', 'success');
                    $('#calendar').fullCalendar('refetchEvents');
                } else if (result.isConfirmed) {
                    Swal.close();
                }
            });
        }

    });

    function renderProgressBars(tasks) {
        const progressSection = $('#task-progress-section');
        progressSection.empty();

        tasks.forEach(task => {
            const progressBar = `
                <div class="mb-3">
                    <p><strong>${task.title}</strong> (${task.progress}%)</p>
                    <div class="progress" data-task-id="${task.id}">
                        <div class="progress-bar" role="progressbar" style="width: ${task.progress}%">
                            ${task.progress}%
                        </div>
                    </div>
                </div>
            `;
            progressSection.append(progressBar);
        });

        $('.progress').click(function () {
            const taskId = $(this).data('task-id');


            const userRole = "{{ auth()->user()->role }}"; 
            if (userRole === 'user') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Permission Denied',
                    
                });
                return;
            }

            Swal.fire({
                title: 'Update Task Status',
                input: 'select',
                inputOptions: {
                    'pending': 'Pending',
                    'in_progress': 'In Progress',
                    'completed': 'Completed'
                },
                inputPlaceholder: 'Select Status',
                showCancelButton: true,
                confirmButtonText: 'Next',
            }).then((result) => {
                if (result.isConfirmed) {
                    const selectedStatus = result.value;

                    if (selectedStatus === 'completed') {
                        Swal.fire({
                            title: 'Add a Comment',
                            html: `
                                <textarea id="comment-text" class="form-control" placeholder="Add your comment here..." rows="3"></textarea>
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Submit',
                            preConfirm: () => {
                                const comment = $('#comment-text').val().trim();
                                if (!comment) {
                                    Swal.showValidationMessage('Comment is required for completed tasks.');
                                }
                                return comment;
                            }
                        }).then((commentResult) => {
                            if (commentResult.isConfirmed) {
                                updateTaskStatus(taskId, selectedStatus, commentResult.value);
                            }
                        });
                    } else {
                        updateTaskStatus(taskId, selectedStatus);
                    }
                }
            });
        });
    }

    function updateTaskStatus(taskId, status, comment = null) {
        $.ajax({
            url: `/tasks/${taskId}/update-status`,
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                status: status,
                comment: comment
            },
            success: function () {
                Swal.fire('Status Updated!', '', 'success');
                $('#calendar').fullCalendar('refetchEvents');
                updateTaskCounts(); 
            },
            error: function () {
                Swal.fire('Error!', 'Failed to update status.', 'error');
            }
        });
    }
    function updateTaskCounts() {
        $.ajax({
            url: "{{ route('tasks.counts') }}",
            method: 'GET',
            success: function (data) {
                $('#completed-tasks-count').text(data.completedTasks);
                $('#pending-tasks-count').text(data.pendingTasks);
                $('#in-progress-tasks-count').text(data.inProgressTasks);
            },
            error: function () {
                console.error('Failed to fetch task counts.');
            }
        });
    }
});

</script>
@endpush
