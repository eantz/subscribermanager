<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\UserField;
use DB;

class FieldTest extends TestCase
{
    protected $currentUser;

    public function setUp()
    {
        parent::setUp();

        $user = new User;
        $user->name = 'Aliando';
        $user->email = 'aliando@gmail.com';
        $user->password = bcrypt('secret');
        $user->api_token = str_random(100);
        $user->save();

        $this->currentUser = $user;
    }

    public function testFieldList()
    {
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('GET', '/api/field/list');

        $responseData = $response->original;

        $response->assertStatus(200)
            ->assertJsonFragment(['fields']);

        $this->assertEquals($responseData['fields'][0]['user_id'], null);
    }

    public function testCreateField()
    {
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('POST', '/api/field/create', [
                        'title' => 'Your Current City',
                        'type' => 'string'
                    ]);

        $responseData = $response->original;

        $response->assertStatus(200);

        $this->assertEquals($responseData['field']['name'], 'your_current_city');
    }

    public function testUpdateField()
    {
        $responseCreate = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('POST', '/api/field/create', [
                        'title' => 'Your Current City',
                        'type' => 'string'
                    ]);

        $responseDataCreate = $responseCreate->original;

        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('PUT', '/api/field/update/' . $responseDataCreate['field']['id'], [
                        'title' => 'Your Current Country',
                    ]);

        $responseData = $response->original;

        $response->assertStatus(200);

        $this->assertEquals($responseData['field']['name'], 'your_current_city');
    }


    public function testRemoveField()
    {
        $responseCreate = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('POST', '/api/field/create', [
                        'title' => 'Your Current City',
                        'type' => 'string'
                    ]);

        $responseDataCreate = $responseCreate->original;

        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('DELETE', '/api/field/remove/' . $responseDataCreate['field']['id']);

        $responseData = $response->original;

        $response->assertStatus(200);

        $this->assertTrue($responseData['status']);
    }

    public function testUserFieldInFieldList()
    {
        $responseCreate = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('POST', '/api/field/create', [
                        'title' => 'Your Current City',
                        'type' => 'string'
                    ]);

        $responseDataCreate = $responseCreate->original;

        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('GET', '/api/field/list');

        $responseData = $response->original;

        $response->assertStatus(200);

        $this->assertEquals($responseData['fields'][count($responseData['fields']) - 1]['id'], 
            $responseDataCreate['field']['id']);
    }
}
