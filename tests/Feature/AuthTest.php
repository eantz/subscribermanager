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
    protected $initialized = false;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();

        DB::table('users')->insert([
            'name' => 'Aliando',
            'email' => 'aliando@gmail.com',
            'password' => bcrypt('secret'),
            'api_token' => str_random(100)
        ]);
    }
    
    public function testLoginFailed()
    {
        $this->assertInvalidCredentials([
            'email' => 'aliando@gmail.com',
            'password' => 'wrongpassword'
        ], 'api');
    }

    public function testLoginSuccess()
    {
        $response = $this->json('POST', '/api/auth/login', [
            'email' => 'aliando@gmail.com',
            'password' => 'secret'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'email' => 'aliando@gmail.com',
            ]);
    }

    public function tearDown()
    {
        DB::rollback();
    }
}
