<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use DB;
use App\User;

class SubscriberTest extends TestCase
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

    public function testSubscriberCreateWithMinimumData()
    {
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('POST', '/api/subscriber/create', [
                        'email' => 'jane.shalimar@gmail.com',
                        'name' => 'Jane Shalimar'
                    ]);

        $responseData = $response->original;

        $response->assertStatus(200);

        $this->assertEquals($responseData['subscriber']['email'], 'jane.shalimar@gmail.com');
        $this->assertEquals($responseData['subscriber']['name'], 'Jane Shalimar');
    }

    public function testShowSubscriber($value='')
    {
        $responseCreate = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('POST', '/api/subscriber/create', [
                        'email' => 'jane.shalimar@gmail.com',
                        'name' => 'Jane Shalimar'
                    ]);

        $responseDataCreate = $responseCreate->original;

        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('GET', '/api/subscriber/show/' . $responseDataCreate['subscriber']['id']);

        $responseData = $response->original;

        $response->assertStatus(200);

        $emailKey = array_search('email', array_column($responseData['fields'], 'name'));

        $this->assertEquals($responseData['subscriber']['email'], 'jane.shalimar@gmail.com');
        $this->assertEquals($responseData['subscriber']['name'], 'Jane Shalimar');
        $this->assertEquals($responseData['fields'][$emailKey]['value'], 'jane.shalimar@gmail.com');
    }

    public function testListSubscriber()
    {
        $responseCreate = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('POST', '/api/subscriber/create', [
                        'email' => 'jane.shalimar@gmail.com',
                        'name' => 'Jane Shalimar'
                    ]);

        $responseDataCreate = $responseCreate->original;

        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('GET', '/api/subscriber/list');

        $responseData = $response->original;

        $response->assertStatus(200);


        $this->assertEquals($responseData['subscribers'][0]['email'], 'jane.shalimar@gmail.com');
    }

    public function testSubscriberUpdate()
    {
        $responseCreate = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('POST', '/api/subscriber/create', [
                        'email' => 'jane.shalimar@gmail.com',
                        'name' => 'Jane Shalimar'
                    ]);

        $responseDataCreate = $responseCreate->original;

        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('PUT', '/api/subscriber/update/' . $responseDataCreate['subscriber']['id'], [
                        'name' => 'Jennifer',
                        'email' => 'jennifer@gmail.com'
                    ]);

        $responseData = $response->original;

        $response->assertStatus(200);
        $this->assertEquals($responseData['subscriber']['email'], 'jennifer@gmail.com');
        $this->assertEquals($responseData['subscriber']['name'], 'Jennifer');
    }

    public function testRemoveSubscriber($value='')
    {
        $responseCreate = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('POST', '/api/subscriber/create', [
                        'email' => 'jane.shalimar@gmail.com',
                        'name' => 'Jane Shalimar'
                    ]);

        $responseDataCreate = $responseCreate->original;

        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->currentUser->api_token
                    ])
                    ->json('DELETE', '/api/subscriber/remove/' . $responseDataCreate['subscriber']['id']);

        $responseData = $response->original;

        $response->assertStatus(200);

        $this->assertTrue($responseData['status']);
    }
}
