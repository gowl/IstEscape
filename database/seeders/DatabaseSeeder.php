<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\EscapeRoom;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Remove the tables before seeding them again
        Schema::disableForeignKeyConstraints();
        User::truncate();
        EscapeRoom::truncate();
        Schema::enableForeignKeyConstraints();

        //Generate Users
        User::factory(50)->create();

        //Generate Escape Rooms
        EscapeRoom::create(
            [
                'theme' => 'The Philosopher\'s Stone',
                'max_participants' => 5,
                'duration' => 90,
            ]
        );

        EscapeRoom::create(
            [
                'theme' => 'Horror Mansion',
                'max_participants' => 8,
                'duration' => 40,
            ]
        );

        EscapeRoom::create(
            [
                'theme' => 'Back to The Past',
                'max_participants' => 3,
                'duration' => 25,
            ]
        );

        //Generate Bookings
        for ($userId = 1; $userId <= User::all()->count(); $userId++) {
            $this->createBooking($userId);
        }
    }

    private function createBooking($userId)
    {
        $escapeRoom = EscapeRoom::find(rand(1, 3));
        $date = Carbon::today()->addDays(
            rand(
                0,
                7
            )
        ); //Random date a week ahead (max)
        $timeslots = EscapeRoom::timeSlots(
            $date,
            config('defaults.opening_hour'),
            config('defaults.closing_hour'),
            $escapeRoom->duration
        ); //List of timeslots from opening time till closing time on a specific date
        $selectedTimeslot = Carbon::parse(collect($timeslots)->random()); //Selecting a random time from list
        $beginsAt = Carbon::parse($date)->hours($selectedTimeslot->hour)->minutes(
            $selectedTimeslot->minute
        ); //Setting starting time for this specific date
        $endsAt = Carbon::parse($beginsAt)->addMinutes($escapeRoom->duration)->toTimeString(
        ); //Setting ending time for this specific date

        //Note: This mock data does not account for the max participants being exceeded in a specific time slot of an escape room

        Booking::create(
            [
                'escape_room_id' => $escapeRoom->id,
                'user_id' => $userId,
                'begins_at' => $beginsAt,
                'ends_at' => $endsAt,
                //note: I won't be applying DoB discount logic to this seeded data cuz it's unnecessary
            ]
        );
    }
}
