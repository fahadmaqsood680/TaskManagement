<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth; // Import Auth for user role checks

class DashboardController extends Controller
{
    /**
     * Filter tasks based on user role
     */
    private function filterTasksByUserRole($query)
    {
        $user = Auth::user();

        if ($user->role === 'manager') {
            $query->where('assigned_by', $user->id)
            ->orWhere('assigned_to',$user->id);
        } elseif ($user->role === 'user') {
            $query->where('assigned_to', $user->id);
        }

        return $query;
    }

    public function index()
    {
        $query = Task::query();
        $query = $this->filterTasksByUserRole($query);
        
        $taskCounts = $query
            ->selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status');

        $completedTasks = $taskCounts->get('completed', 0);
        $pendingTasks = $taskCounts->get('pending', 0);
        $inProgressTasks = $taskCounts->get('in_progress', 0);

        return view('dashboard', compact('completedTasks', 'pendingTasks', 'inProgressTasks'));
    }


    public function getCalendarData()
    {
        $query = Task::with(['comments', 'assignedTo']);
        $query = $this->filterTasksByUserRole($query);

        $tasks = $query->get();

        $events = $tasks->map(function ($task) {
            $progress = match ($task->status) {
                'pending' => 0,
                'in_progress' => 50,
                'completed' => 100,
                default => 0,
            };

            $comments = $task->comments->map(fn($comment) => [
                'user' => $comment->user->name ?? 'Unknown User',
                'content' => $comment->content,
                'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
            ]);

            return [
                'id' => $task->id,
                'title' => $task->title,
                'start' => $task->due_date,
                'url' => route('tasks.edit', $task->id),
                'color' => $this->getPriorityColor($task->priority),
                'status' => $task->status,
                'progress' => $progress,
                'comments' => $comments,
                'assigned_user' => $task->assignedTo->name ?? 'Unassigned',
            ];
        });

        return response()->json($events);
    }

    private function getPriorityColor($priority)
    {
        return match ($priority) {
            'High' => '#e3342f',
            'Medium' => '#f6993f',
            'Low' => '#38c172',
            default => '#6cb2eb',
        };
    }

        public function getTaskCounts()
    {
        $query = Task::query();
        $query = $this->filterTasksByUserRole($query);

        $taskCounts = $query
            ->selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status');

        return response()->json([
            'completedTasks' => $taskCounts['completed'] ?? 0,
            'pendingTasks' => $taskCounts['pending'] ?? 0,
            'inProgressTasks' => $taskCounts['in_progress'] ?? 0,
        ]);
    }

}
