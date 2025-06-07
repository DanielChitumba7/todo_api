<?php

namespace App\Services;

use App\Repositories\TaskRepository;

class TaskService
{
    protected $TaskRepository;

    public function __construct(TaskRepository $TaskRepository)
    {
        $this->TaskRepository = $TaskRepository;
    }

    public function GetAll()
    {
        return $this->TaskRepository->GetAll();
    }

    public function GetById(string $id)
    {
        return $this->TaskRepository->GetById($id);
    }

    public function Create(array $data)
    {
        return $this->TaskRepository->Create($data);
    }

    public function Edit(string $id, array $data)
    {
        return $this->TaskRepository->Edit($id, $data);
    }

    public function Delete(string $id)
    {
        return $this->TaskRepository->Delete($id);
    }
    
    public function filterBystatus(string $status)
    {
        return $this->TaskRepository->filterBystatus($status);
    }
}
