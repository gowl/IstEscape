<?php

use App\Http\Controllers\EscapeRoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Public Routes
Route::get('/escape-rooms', [EscapeRoomController::class, 'index']);
Route::get('/escape-rooms/{id}', [EscapeRoomController::class, 'show']);
Route::get('/escape-rooms/{id}/time-slots', [EscapeRoomController::class, 'timeSlots'])->name('time-slots');

Route::get('/bookings', [EscapeRoomController::class, 'index']);

//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/bookings', [EscapeRoomController::class, 'store']);
    Route::delete('/bookings/{id}', [EscapeRoomController::class, 'destroy']);
});
