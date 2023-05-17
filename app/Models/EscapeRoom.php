<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscapeRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme',
        'max_participants',
        'duration'
    ];

    public function getTimeSlotsAttribute()
    {
        return $this->timeSlots(now(), config('defaults.opening_hour'), config('defaults.closing_hour'),
            $this->duration);
    }

    public static function timeSlots($date, $startHour, $endHour, $duration)
    {
        $date = Carbon::parse($date->startOfDay()); //Reset the time to 00:00:00
        $endDate = Carbon::parse($date)->hour($endHour); //When the store opens
        $dateTime = Carbon::parse($date)->hour($startHour); //When the store closes
        $allTimes = [];
        $allTimes[] = $dateTime->toTimeString(); //Add the starting timeslot
        while ($dateTime->lt($endDate)) {
            $dateTime->addMinutes($duration); //Add increments of timeslots
            $allTimes[] = $dateTime->toTimeString();
        }
        array_pop($allTimes); //Last element is exactly when the store closes, so we don't want that

        return $allTimes;
    }
}
