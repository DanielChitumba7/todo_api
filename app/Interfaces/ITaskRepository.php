<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface ITaskRepository
{
     public function register(array $data);
    public function login(string $email, string $password);
    public function forgotPassword(string $email);
    public function logout(Request $request);
}
