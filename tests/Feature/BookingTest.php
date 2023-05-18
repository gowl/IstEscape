<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_bookings_create_endpoint_works()
    {
        $this->actingAs(User::find(1)); //Doing the test as the first user in the DB

        $response = $this->createBooking();

        $response->assertStatus(201)->assertJson(
            fn(AssertableJson $json) => $json
                ->where('escape_room_id', '3')
                ->etc()
        );
    }

    public function test_bookings_index_endpoint_works()
    {
        $this->actingAs(User::find(1)); //Doing the test as the first user in the DB
        $response = $this->get('/api/bookings');
        $response->assertStatus(200);
    }

    public function test_bookings_delete_endpoint_works()
    {
        $user = User::find(1);
        $this->actingAs($user); //Doing the test as the first user in the DB

        $booking = Booking::create(
            [
                'escape_room_id' => 3,
                'user_id' => $user->id,
                'begins_at' => '2023/05/17 17:30:00',
                'ends_at' => '2023/05/17 17:55:00',
            ]
        );

        $response = $this->delete(
            '/api/bookings/' . $booking->id,
        );

        $response->assertStatus(200);
    }

    public function test_double_booking_prevention_works()
    {
        $this->actingAs(User::find(1)); //Doing the test as the first user in the DB

        //Book the same slot twice
        for ($i = 0; $i < 2; $i++) {
            $response = $this->createBooking();
        }

        $response->assertStatus(400)->assertJson(
            fn(AssertableJson $json) => $json
                ->where('message', 'User already booked in this timeslot!')
        );
    }

    public function test_overbooking_An_Escape_Room_prevention_works()
    {
        Booking::whereEscapeRoomId(3)->where('begins_at', Carbon::today()->toDateString() . ' 17:30:00')->delete(
        ); //Making sure there are no bookings at this exact timeslot

        //Book the same slot twice
        for ($id = 1; $id < 5; $id++) { //Escape Room 3's max participants are 3, so the 4th one shouldn't be allowed
            $this->actingAs(User::find($id)); //Doing the test as different users
            $response = $this->createBooking();
        }

        $response->assertStatus(400)->assertJson(
            fn(AssertableJson $json) => $json
                ->where('message', 'This timeslot is overbooked!')
        );
    }


    public function test_booking_discount_on_birthday_works()
    {
        $user = User::find(1);
        $this->actingAs($user); //Doing the test as the first user in the DB
        $user->dob = today()->year(1990)->toDateString(
        ); //Setting the birthday of the user to be the same as the same day the user is booking
        $user->save();

        $response = $this->createBooking();
        $response->assertStatus(201)->assertJson(
            fn(AssertableJson $json) => $json
                ->where('escape_room_id', '3')
                ->where('discount', 10)
                ->etc()
        );
    }

    private function createBooking()
    {
        $response = $this->post(
            '/api/bookings',
            [
                'escape_room_id' => '3',
                'begins_at' => Carbon::today()->toDateString() . 'T17:30:00.000000Z',
            ]
        );
        return $response;
    }
}
