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
        //remove the tables before seeding them again
        Schema::disableForeignKeyConstraints();
        User::truncate();
        EscapeRoom::truncate();
        Schema::enableForeignKeyConstraints();

        //generate records
        User::factory(50)->create();

        EscapeRoom::create([
            'theme' => 'The Philosopher\'s Stone',
            'max_participants' => 5,
        ]);

        EscapeRoom::create([
            'theme' => 'Horror Mansion',
            'max_participants' => 8,
        ]);

        EscapeRoom::create([
            'theme' => 'Back to The Past',
            'max_participants' => 3,
        ]);

        for ($userId = 1; $userId <= User::all()->count(); $userId++) {
            $this->createBooking($userId);
        }
    }

    private function createBooking($userId)
    {
        //random date a week ahead (max) and within opening hours (11am-9pm)
        $beginsAt = Carbon::today()->hour(rand(11, 20))->addDays(rand(0, 7));
        $endsAt = Carbon::parse($beginsAt)->addHour();
        //Note: This mock data does not account for the max participants being exceeded in a specific time slot of an escape room

        Booking::create([
            'escape_room_id' => rand(1, 3),
            'user_id' => $userId,
            'begins_at' => $beginsAt,
            'ends_at' => $endsAt,
        ]);
    }
}
