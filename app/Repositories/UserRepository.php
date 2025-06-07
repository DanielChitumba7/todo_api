<?php

namespace App\Repositories;

use App\Interfaces\IUserRepository;
use App\Models\User;
use Faker\Provider\ar_EG\Person;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class UserRepository implements IUserRepository
{
   public function login(string $email, string $password)
{
    if (Auth::attempt(['email' => $email, 'password' => $password])) {
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    return null;
}



public function register(array $data)
{
    return DB::transaction(function () use ($data) {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    });
}

    public function forgotPassword(string $email)
    {
        return Password::sendResetLink(['email' => $email]);
    }

    public function logout(Request $request)
    {

        $token = $request->bearerToken();
        if (!$token) {
           return false;
        }

        $access_token= PersonalAccessToken::findToken($token);
        if (!$access_token) {
            return false;   
    }
        $access_token->delete();
        return true;
    }
}
