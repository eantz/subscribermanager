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
    protected $newField;

    protected $authorizationHeader;

    public function setUp()
    {
        parent::setUp();

        $this->currentUser = factory(User::class)->create();
        $this->newField = factory(UserField::class)->create();

        $this->authorizationHeader = ['Authorization' => 'Bearer ' . $this->currentUser->api_token];
    }

    public function testFieldList()
    {
        $response = $this->withHeaders($this->authorizationHeader)
                    ->json('GET', '/api/field/list')
                    ->assertStatus(200)
                    ->assertJsonStructure([
                        'fields' => [
                            '*' => [
                                'id',
                                'user_id',
                                'title',
                                'type',
                                'placeholder'
                            ]
                        ]
                    ])
                    ->decodeResponseJson();

        $this->assertEquals($response['fields'][0]['user_id'], null);
    }

    public function testCreateField()
    {
        $response = $this->withHeaders($this->authorizationHeader)
                    ->json('POST', '/api/field/create', [
                        'title' => 'Your Current City',
                        'type' => 'string'
                    ])
                    ->assertStatus(200)
                    ->assertJsonStructure([
                        'field' => [
                            'id',
                            'user_id',
                            'title',
                            'type',
                            'placeholder'
                        ]
                    ])
                    ->decodeResponseJson();

        $this->assertEquals($response['field']['name'], 'your_current_city');
    }

    public function testUpdateField()
    {
        $this->newField->user_id = $this->currentUser->id;
        $this->newField->save();

        $response = $this->withHeaders($this->authorizationHeader)
                    ->json('PUT', '/api/field/update/' . $this->newField->id, [
                        'title' => 'Your Current Country',
                    ])
                    ->assertStatus(200)
                    ->decodeResponseJson();

        $this->assertEquals($response['field']['name'], $this->newField->name);
    }


    public function testRemoveField()
    {
        $this->newField->user_id = $this->currentUser->id;
        $this->newField->save();

        $response = $this->withHeaders($this->authorizationHeader)
                    ->json('DELETE', '/api/field/remove/' . $this->newField->id)
                    ->assertStatus(200)
                    ->decodeResponseJson();

        $this->assertTrue($response['status']);
    }

    public function testUserFieldInFieldList()
    {
        $this->newField->user_id = $this->currentUser->id;
        $this->newField->save();

        $response = $this->withHeaders($this->authorizationHeader)
                    ->json('GET', '/api/field/list')
                    ->assertStatus(200)
                    ->decodeResponseJson();

        $this->assertEquals($response['fields'][count($response['fields']) - 1]['id'], 
            $this->newField->id);
    }
}
