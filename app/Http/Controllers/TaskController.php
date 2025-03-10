<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TaskRequest;
use DataTables;
class TaskController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
        public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Task::with(['assignedBy', 'assignedTo']);
            $user = Auth::user();

            if ($user->role === 'admin') {
                $query;
            } 
          
            elseif ($user->role === 'manager') {
                $query->where('assigned_by', $user->id)
                       ->orWhere('assigned_to',$user->id);
            }

            // Custom Filters
            if (!empty($request->title)) {
                $query->where('title', 'like', "%{$request->title}%");
            }

            if (!empty($request->priority)) {
                $query->where('priority', 'like', "%{$request->priority}%");
            }

            if (!empty($request->due_date)) {
                $query->whereDate('due_date', $request->due_date);
            }

            $totalRecords = Task::count();
            $filteredRecords = $query->count(); 
            // Pagination Logic
            $tasks = $query->offset($request->input('start'))
                        ->limit($request->input('length'))
                        ->get();

            return response()->json([
                'draw' => intval($request->get('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $tasks,
            ]);
        }
        return view('pages.tasks.index');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->getCategoriesAndUsers();
        return view('pages.tasks.add-edit-task', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $task = Task::create([
            'title'        => $request->title,
            'description'  => $request->description,
            'priority'     => $request->priority,
            'due_date'     => $request->due_date,
            'category_id'  => $request->category_id,
            'assigned_to'  => $request->assigned_to,
            'assigned_by'  => auth()->id(), 
            'status'       => $request->status,
        ]);
        return redirect()->route('tasks.index')->with('success', 'Task added successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('view', $task);

        $data = $this->getCategoriesAndUsers();
        $data['task'] = $task;

        return view('pages.tasks.add-edit-task', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, string $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $task->status = $request->status;
        $task->save();

        if ($request->status === 'completed' && $request->filled('comment')) {
            $task->comments()->create([
                'content' => $request->comment,
                'user_id' => auth()->id(),
            ]);
        }

        return response()->json(['success' => true]);
    }

     /**
     * Common method for fetching categories and users
     */
    private function getCategoriesAndUsers()
    {
        $user = Auth::user();

        $categories = ($user->role === 'admin')
            ? Category::all()
            : Category::where('user_id', $user->id)->get();

        $users = User::select('id', 'name', 'email', 'role')
            ->when($user->role === 'admin', function ($query) use ($user) {
                $query->where('id', '<>', $user->id);
            })
            ->when($user->role === 'manager', function ($query) use ($user) {
                $query->where('added_by', $user->id);
            })
            ->get();

        return [
            'categories' => $categories,
            'users' => $users
        ];
    }


}
