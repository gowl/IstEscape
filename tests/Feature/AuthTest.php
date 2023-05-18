<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_register_endpoint_works()
    {
        $response = $this->post(
            '/api/register',
            [
                'name' => 'Steven Doe',
                'email' => 'stevendoe@gmail.com',
                'password' => 'password',
                'dob' => '1994/01/25'
            ]
        );

        $response->assertStatus(201);
    }

    public function test_login_endpoint_works()
    {
        //Create user
        User::factory()->create(
            [
                'name' => 'John Doe',
                'email' => 'johndoe@gmail.com',
                'password' => '$2y$10$WBcxd8ewQSBJKfc25mnKoeBlXtB1vwOrCXAe.krGg8PrpiKXIwtcG',
                'dob' => '1994/04/02'
            ]
        );

        //Check if can login as user in the system
        $response = $this->post(
            '/api/login',
            [
                'email' => 'johndoe@gmail.com',
                'password' => 'password',
            ]
        );
        $response->assertStatus(200);
    }
}
