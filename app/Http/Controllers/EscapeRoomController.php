<?php

namespace App\Http\Controllers;

use App\Models\EscapeRoom;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     * @param EscapeRoom $escapeRoom
     * @return Response
     */
    public function show($escapeRoom)
    {
        $escapeRoom = EscapeRoom::find($escapeRoom);
        if (is_null($escapeRoom)) {
            return response(
                [
                    'message' => 'Escape Room not found'
                ],
                404
            );
        }

        return $escapeRoom;
    }

    public function timeSlots($escapeRoom)
    {
        $escapeRoom = EscapeRoom::find($escapeRoom);
        if (is_null($escapeRoom)) {
            return response(
                [
                    'message' => 'Escape Room not found'
                ],
                404
            );
        }

        return [
            'timeslots' => $escapeRoom->timeSlots,
        ];
    }
}
