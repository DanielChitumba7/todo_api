<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserService
{
    protected $UserRepository;
    public function __construct(UserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }
   
      public function register(array $data)
    {
        return $this->UserRepository->register($data);
    }

    public function login(string $email, string $password)
    {
        return $this->UserRepository->login($email, $password);
    }

    public function logout(Request $request)
    {
        return $this->UserRepository->logout($request);
    }
}
