<?php

namespace Tests\Feature;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository();
    }

     #[Test]
    public function it_can_login_a_user_with_valid_credentials()
    {
        $password = '12345678';
        $user = User::factory()->create([
            'email' => 'Demerson474@gmail.com',
            'password' => Hash::make($password),
        ]);

        $result = $this->userRepository->login('Demerson474@gmail.com', $password);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($user->id, $result['user']->id);
        $this->assertNotNull($result['token']);
    }

    #[Test]
    public function it_returns_null_for_invalid_login_credentials()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $result = $this->userRepository->login('test@example.com', 'wrongpassword');

        $this->assertNull($result);
    }

     #[Test]
    public function it_can_register_a_new_user()
    {
        $userData = [
            'name' => 'DanielChitumba',
            'email' => 'ChitumbaDaniel@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ];

        $result = $this->userRepository->register($userData);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($userData['email'], $result['user']->email);
        $this->assertNotNull($result['token']);
        $this->assertDatabaseHas('users', ['email' => 'ChitumbaDaniel@gmail.com']);
    }

     #[Test]
    public function it_can_logout_an_authenticated_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $request = new \Illuminate\Http\Request();
        $request->headers->set('Authorization', 'Bearer ' . $token);

        $result = $this->userRepository->logout($request);

        $this->assertTrue($result);
        $this->assertNull(PersonalAccessToken::findToken($token));
    }

    #[Test]
    public function it_returns_false_if_no_token_is_provided_for_logout()
    {
        $request = new \Illuminate\Http\Request();
        $result = $this->userRepository->logout($request);
        $this->assertFalse($result);
    }

    #[Test]
    public function it_returns_false_if_invalid_token_is_provided_for_logout()
    {
        $request = new \Illuminate\Http\Request();
        $request->headers->set('Authorization', 'Bearer invalid_token');
        $result = $this->userRepository->logout($request);
        $this->assertFalse($result);
    }
}


