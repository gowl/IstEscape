<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\EscapeRoom;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = auth()->user()->bookings;

        if ($bookings->isEmpty()) { //Check if array is empty
            return response([
                'message' => 'User has no bookings'
            ], 200);
        }

        return $bookings;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate submitted fields
        $fields = $request->validate([
            'escape_room_id' => 'required',
            'begins_at' => 'required',
        ]);

        $escapeRoom = EscapeRoom::find($fields['escape_room_id']);

        //Check if such Escape Room exists
        if (is_null($escapeRoom)) {
            return response([
                'message' => 'Escape Room Not Found'
            ], 400);
        }

        //Create some variables
        $selectedTimeSlot = Carbon::parse(($fields['begins_at']));
        $selectedTimeSlotDay = $selectedTimeSlot->format('m/d');
        $selectedTimeSlotTime = $selectedTimeSlot->toTimeString();
        //Check if such timeslot exists in Escape Room
        if ( ! in_array($selectedTimeSlotTime, $escapeRoom->timeslots)) {
            return response([
                'message' => 'Unavailable Timeslot'
            ], 404);
        }

        $user = auth()->user();

        //Make sure the user doesn't double book in this timeslot
        if(!is_null(Booking::whereUserId($user->id)->where('begins_at', $selectedTimeSlot)->first())){
            return response([
                'message' => 'User already booked in this timeslot!'
            ], 400);
        }

        //Make sure the maximum participants for this timeslot have not been reached
        $bookingsForThisTimeSlotCount = Booking::whereEscapeRoomId($escapeRoom->id)->where('begins_at', $selectedTimeSlot)->count();
        if($bookingsForThisTimeSlotCount >= $escapeRoom->max_participants){
            return response([
                'message' => 'This timeslot is overbooked!'
            ], 400);
        }

        //Create booking
        return Booking::create([
            'escape_room_id' => $fields['escape_room_id'],
            'user_id' => $user->id,
            'begins_at' => $fields['begins_at'],
            'discount' => $selectedTimeSlotDay == $user->dob->format('m/d'), //Give the user a discount if today is their birthday
            'ends_at' => Carbon::parse($fields['begins_at'])->addMinutes($escapeRoom->duration),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy($booking)
    {
        $booking = Booking::find($booking);
        if (is_null($booking)) {
            return response([
                'message' => 'Booking not found!'
            ], 404);
        }
        if ($booking->user_id == auth()->user()->id) { //Check if array is empty
            Booking::destroy($booking->id);

            return response([
                'message' => 'Booking successfully deleted!'
            ], 200);
        }

        return response([
            'message' => 'User doesn\'t own this booking'
        ], 403);
    }
}
