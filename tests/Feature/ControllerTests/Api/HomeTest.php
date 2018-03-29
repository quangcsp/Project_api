<?php

namespace Tests\Feature\ControllerTests\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use DatabaseTransactions;

    public function testListBookInHomePageSuccess()
    {
        $headers = $this->getHeaders();

        $response = $this->call('GET', route('api.v0.home'), [], [], [], $headers);
        $response->assertJsonStructure([
            'items' => [
                ['key', 'title', 'data']
            ],
            'message' => [
                'status', 'code',
            ],
        ])->assertJson([
            'message' => [
                'status' => true,
                'code' => 200,
            ],
        ])->assertStatus(200);
    }

    public function testFilterBookInHomePageSuccess()
    {
        $headers = $this->getHeaders();
        $data['filters'] = [
            ['category' => [2, 3]],
            ['office' => [4]],
        ];

        $response = $this->call('POST', route('api.v0.homeFilters'), $data, [], [], $headers);
        $response->assertJsonStructure([
            'items' => [
                ['key', 'title', 'data']
            ],
            'message' => [
                'status', 'code',
            ],
        ])->assertJson([
            'message' => [
                'status' => true,
                'code' => 200,
            ]
        ])->assertStatus(200);
    }

    public function testFilterBookInHomePageWithFiltersInValid()
    {
        $headers = $this->getHeaders();
        $data['filters'] = 'a';

        $response = $this->call('POST', route('api.v0.homeFilters'), $data, [], [], $headers);
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
