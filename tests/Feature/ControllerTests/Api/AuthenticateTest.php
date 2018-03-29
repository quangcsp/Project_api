<?php

namespace Tests\Feature\ControllerTests\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticateTest extends TestCase
{
    /* TEST STORE BOOKS */

/**    public function testLoginSuccess()
    {
        $headers = $this->getHeaders();
        $data['email'] = env('AUTH_EMAIL_TEST');
        $data['password'] = env('AUTH_PASSWORD_TEST');

        $response = $this->call('POST', route('api.v0.login'), $data, [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code',
            ],
        ])->assertJson([
            'message' => [
                'status' => true,
                'code' => 200,
            ]
        ])->assertStatus(200);
    } */

    public function testLoginWithFieldsNull()
    {
        $headers = $this->getHeaders();

        $response = $this->call('POST', route('api.v0.login'), [], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 422,
            ]
        ])->assertStatus(422);
    }
}
