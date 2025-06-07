<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface IUserRepository
{
    public function register(array $data);
    public function login(string $email, string $password);
    public function forgotPassword(string $email);
    public function logout(Request $request);
}
