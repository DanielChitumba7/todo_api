<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;

/**
 * @OA\Tag(
 *     name="Autenticação",
 *     description="Endpoints para autenticação de usuários"
 * )
 */
class AuthController extends Controller
{
    protected $UserService;

    public function __construct(UserService $UserService)
    {
        $this->UserService = $UserService;
    }

    /**
     * @OA\Post(
     *     path="/user/register",
     *     tags={"Autenticação"},
     *     summary="Registra um novo usuário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="Daniel"),
     *             @OA\Property(property="email", type="string", example="daniel@email.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Usuário registrado com sucesso"),
     *     @OA\Response(response=500, description="Erro ao registrar usuário")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/user/login",
     *     tags={"Autenticação"},
     *     summary="Autentica um usuário e retorna o token JWT",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="daniel@email.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *    @OA\Schema(
     *   schema="User",
    *   @OA\Property(property="id", type="string"),
    *   @OA\Property(property="name", type="string"),
    *   @OA\Property(property="email", type="string"),
    *   
    * ), 
     *     @OA\Response(response=200, description="Login bem-sucedido"),
     *     @OA\Response(response=401, description="Credenciais inválidas")
     * )
     */
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



    /**
     * @OA\Post(
     *     path="/user/logout",
     *     tags={"Autenticação"},
     *     summary="Termina a sessão do usuário autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Logout bem-sucedido"),
     *     @OA\Response(response=401, description="Falha no logout")
     * )
     */
    public function logout(Request $request)
    {
        $success = $this->UserService->logout($request);

        if (!$success) {
            return response()->json(['message' => 'Logout failed'], 401);
        }

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
