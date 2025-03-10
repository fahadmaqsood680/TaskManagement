<?php

namespace App\Http\Controllers;


use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
      
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = new Comment([
            'content' => $request->content,
            'user_id' => auth()->id(),  // Assuming authenticated user
        ]);

        $task->comments()->save($comment);

        return response()->json(['success' => true, 'message' => 'Comment added successfully.']);
    }
}
