<?php

namespace App\Interfaces;


interface ITaskRepository
{
     public function GetAll();
    public function GetById(string $id);
    public function filterBystatus(string $status);
    public function Create(array $data);
    public function Edit(string $id, array $data);
    public function Delete(string $id);
}
