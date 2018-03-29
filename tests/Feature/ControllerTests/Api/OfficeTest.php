<?php

namespace Tests\Feature\ControllerTests\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OfficeTest extends TestCase
{
    use DatabaseTransactions;

    public function testListOfficesSuccess()
    {
        $headers = $this->getHeaders();
        $response = $this->call('GET', route('api.v0.categories.index'), [], [], [], $headers);
        
        $response->assertJsonStructure([
            'message' => [
                'status', 'code'
            ],
        ])->assertJson([
            'message' => [
                'status' => true,
                'code' => 200,
            ]
        ])->assertStatus(200);
    }
}
