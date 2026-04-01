<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->tasks;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        $task = $request->user()->tasks()->create([
            'title' => $request->title
        ]);

        return response()->json($task, 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,done'
        ]);

        $task = $request->user()->tasks()->findOrFail($id);

        $task->update([
            'status' => $request->status
        ]);

        return response()->json($task);
    }
}
