<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;


class TaskController extends Controller

{

        protected $TaskService;

        public function __construct(TaskService $TaskService)
        {
            $this->TaskService = $TaskService;
        }

  
    public function index()
    {
        $tasks = $this->TaskService->GetAll();
        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No tasks found'], 404);
        }
        return response()->json($tasks, 200);
    }

    public function create()
    {
        return view('tasks.create');
    }


    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        $task = $this->TaskService->create($data);
        if (!$task) {
            return response()->json(['message' => 'Failed to create task'], 500);
        }
        return response()->json(['message' => 'Task created successfully', 'task' => $task], 201);
    }

  
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,completed',
        ]);

        $task = $this->TaskService->Edit($id, $data);
        if (!$task) {
            return response()->json(['message' => 'Failed to update task'], 500);
        }
        return response()->json(['message' => 'Task updated successfully', 'task' => $task]);
    }

  
    public function destroy(string $id)
    {
        $task = $this->TaskService->Delete($id);
        if (!$task) {
            return response()->json(['message' => 'Failed to delete task'], 500);
        }
        return response()->json(['message' => 'Task deleted successfully']);
    }

  
    public function filterByStatus(string $status)
    {
        $tasks = $this->TaskService->filterBystatus($status);
        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No tasks found with the specified status'], 404);
        }
        return response()->json($tasks);
    }
}