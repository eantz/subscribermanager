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

    protected $authorizationHeader;

    public function setUp()
    {
        parent::setUp();

        $this->currentUser = factory(User::class)->create();

        $this->authorizationHeader = ['Authorization' => 'Bearer ' . $this->currentUser->api_token];
    }

    public function testSubscriberCreateWithMinimumData()
    {
        $response = $this->withHeaders($this->authorizationHeader)
                    ->json('POST', '/api/subscriber/create', [
                        'email' => 'jane.shalimar@gmail.com',
                        'name' => 'Jane Shalimar'
                    ])
                    ->assertStatus(200)
                    ->assertJsonStructure([
                        'subscriber' => [
                            'email',
                            'name'
                        ]
                    ])
                    ->decodeResponseJson();

        $this->assertEquals($response['subscriber']['email'], 'jane.shalimar@gmail.com');
        $this->assertEquals($response['subscriber']['name'], 'Jane Shalimar');
    }

    public function testShowSubscriber($value='')
    {
        $responseCreate = $this->withHeaders($this->authorizationHeader)
                    ->json('POST', '/api/subscriber/create', [
                        'email' => 'jane.shalimar@gmail.com',
                        'name' => 'Jane Shalimar'
                    ])
                    ->decodeResponseJson();

        $response = $this->withHeaders($this->authorizationHeader)
                    ->json('GET', '/api/subscriber/show/' . $responseCreate['subscriber']['id'])
                    ->assertStatus(200)
                    ->assertJsonStructure([
                        'subscriber',
                        'fields' => [
                            '*' => [
                                'id',
                                'field_id',
                                'title',
                                'type',
                                'value'
                            ]
                        ]
                    ])
                    ->decodeResponseJson();

        $emailKey = array_search('email', array_column($response['fields'], 'name'));

        $this->assertEquals($response['subscriber']['email'], 'jane.shalimar@gmail.com');
        $this->assertEquals($response['subscriber']['name'], 'Jane Shalimar');
        $this->assertEquals($response['fields'][$emailKey]['value'], 'jane.shalimar@gmail.com');
    }

    public function testListSubscriber()
    {
        $responseCreate = $this->withHeaders($this->authorizationHeader)
                    ->json('POST', '/api/subscriber/create', [
                        'email' => 'jane.shalimar@gmail.com',
                        'name' => 'Jane Shalimar'
                    ])
                    ->decodeResponseJson();

        $response = $this->withHeaders($this->authorizationHeader)
                    ->json('GET', '/api/subscriber/list')
                    ->assertStatus(200)
                    ->assertJsonStructure([
                        'subscribers' => [
                            '*' => [
                                'id',
                                'email',
                                'name',
                                'state'
                            ]
                        ]
                    ])
                    ->decodeResponseJson();

        $this->assertEquals($response['subscribers'][0]['email'], 'jane.shalimar@gmail.com');
    }

    public function testSubscriberUpdate()
    {
        $responseCreate = $this->withHeaders($this->authorizationHeader)
                    ->json('POST', '/api/subscriber/create', [
                        'email' => 'jane.shalimar@gmail.com',
                        'name' => 'Jane Shalimar'
                    ])
                    ->decodeResponseJson();

        $response = $this->withHeaders($this->authorizationHeader)
                    ->json('PUT', '/api/subscriber/update/' . $responseCreate['subscriber']['id'], [
                        'name' => 'Jennifer',
                        'email' => 'jennifer@gmail.com'
                    ])
                    ->assertStatus(200)
                    ->decodeResponseJson();

        $this->assertEquals($response['subscriber']['email'], 'jennifer@gmail.com');
        $this->assertEquals($response['subscriber']['name'], 'Jennifer');
    }

    public function testRemoveSubscriber($value='')
    {
        $responseCreate = $this->withHeaders($this->authorizationHeader)
                    ->json('POST', '/api/subscriber/create', [
                        'email' => 'jane.shalimar@gmail.com',
                        'name' => 'Jane Shalimar'
                    ])->decodeResponseJson();

        $response = $this->withHeaders($this->authorizationHeader)
                    ->json('DELETE', '/api/subscriber/remove/' . $responseCreate['subscriber']['id'])
                    ->assertStatus(200)
                    ->decodeResponseJson();

        $this->assertTrue($response['status']);
    }
}
