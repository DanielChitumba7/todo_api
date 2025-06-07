<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;

class AuthController extends Controller
{
     protected $UserService;

    public function __construct(UserService $UserService)
    {
        $this->UserService = $UserService;
    }

   public function register(StoreUserRequest $request)
{
    $data = $request->validated();
    $result = $this->UserService->register($data);

    if (!$result || !isset($result['user'])) {
        return response()->json([
            'message' => 'Failed to register user'
        ], 500);
    }

    return response()->json([
        'message' => 'User registered successfully',
        'user' => $result['user'],
        'token' => $result['token']
    ], 201);
}

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = $this->UserService->login($credentials['email'], $credentials['password']);
        if (!$user) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
        return response()->json([
            'message' => 'Login successful',
            'user' => $user
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $email = $request->input('email');
        $result = $this->UserService->forgotPassword($email);
        if (!$result) {
            return response()->json([
                'message' => 'Failed to send password reset link'
            ], 500);
        }
        return response()->json([
            'message' => 'Password reset link sent successfully'
        ], 200);
    }

 public function logout(Request $request)
{
    $success = $this->UserService->logout($request);

    if (!$success) {
        return response()->json(['message' => 'Logout failed'], 401);
    }

    return response()->json(['message' => 'Logged out successfully'], 200);
}

}
