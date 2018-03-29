<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Eloquent\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
    }

    public function getHeaders($header = [])
    {
        $default = [
            'Accept' => 'application/json',
        ];

        $headers = count($header) ? array_merge($default, $header) : $default;

        return $this->transformHeadersToServerVars($headers);
    }

    public function getFauthHeaders()
    {
        $response = $this->call('POST',
            route('api.v0.login'),
            ['email' => env('AUTH_EMAIL_TEST'), 'password' => env('AUTH_PASSWORD_TEST')],
            [],
            [],
            $this->getHeaders()
        );
        $accessToken = $response->baseResponse->original['fauth']['access_token'];

        return $this->getHeaders(['Authorization' => $accessToken]);
    }

    public function createUser()
    {
        return factory(User::class)->create();
    }
}
