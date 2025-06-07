<?php

namespace App\Repositories;

use App\Interfaces\ITaskRepository;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskRepository implements ITaskRepository
{
     public function GetAll()
    {
        return Task::where('user_id', Auth::id())->get(); 
    }

    public function GetById(string $id)
    {
        return Task::findOrFail($id);
    }
    public function Create(array $data)
    {
        $user = Auth::id();
        $data['user_id'] = $user;
        return Task::create($data);
    }

    public function Edit(string $id, array $data)
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task;
    }

    public function Delete(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return $task;
    }
    public function filterBystatus(string $status)
    {
        $tasks = Task::where('user_id', Auth::user()->id)
                ->where('status', $status)
                ->get();
        return $tasks;
    }
}
