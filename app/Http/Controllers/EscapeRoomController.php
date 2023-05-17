<?php

namespace App\Http\Controllers;

use App\Models\EscapeRoom;
use Illuminate\Http\Request;

class EscapeRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return EscapeRoom::all();
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EscapeRoom  $escapeRoom
     * @return \Illuminate\Http\Response
     */
    public function show(EscapeRoom $escapeRoom)
    {
        return $escapeRoom;
    }

    public function timeSlots(EscapeRoom $escapeRoom)
    {
        $escapeRoom['timeslots'] = $escapeRoom->timeSlots; //Adding the timeslots with the rest of the escapeRoom data for convenience

        return [
            $escapeRoom,
        ];
    }
}
