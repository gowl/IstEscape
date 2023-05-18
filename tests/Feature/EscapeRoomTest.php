<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class EscapeRoomTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_escape_rooms_index_endpoint_works()
    {
        $response = $this->get('/api/escape-rooms');
        $response->assertStatus(200)
            ->assertJson(
                fn(AssertableJson $json) => $json->has(
                    3
                ) //Make sure the seeder has seeded the default escape rooms properly
            );
    }

    public function test_escape_rooms_show_endpoint_works()
    {
        //Test that the
        $response = $this->get('/api/escape-rooms/3');
        $response->assertStatus(200)
            ->assertJson(
                fn(AssertableJson $json) => $json->where('id', 3)
                    ->where('theme', 'Back to The Past')
                    ->where('max_participants', 3)
                    ->where('duration', 25)
                    ->etc()
            );
    }

    public function test_escape_rooms_timeslots_endpoint_works()
    {
        $response = $this->get('/api/escape-rooms/3/time-slots');
        $response->assertStatus(200)
            ->assertJson(
                fn(AssertableJson $json) => $json->has('timeslots') //Make sure key "timeslots" exists
            );
    }
}
