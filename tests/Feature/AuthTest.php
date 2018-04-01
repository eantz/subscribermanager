<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Auth;
use App\User;
use DB;

class AuthTest extends TestCase
{
    protected $newUser;

    public function setUp()
    {
        parent::setUp();

        $this->newUser = factory(User::class)->create();
    }
    
    public function testLoginFailed()
    {
        $response = $this->json('POST', '/api/auth/login', [
            'email' => $this->newUser->email,
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(422);
    }

    public function testLoginSuccess()
    {
        $response = $this->json('POST', '/api/auth/login', [
            'email' => $this->newUser->email,
            'password' => 'secret'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'email',
                    'api_token'
                ]
            ])
            ->assertJsonFragment([
                'email' => $this->newUser->email,
            ]);
    }
}
