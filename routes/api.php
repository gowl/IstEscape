<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/escape-rooms', [EscapeRoomController::class, 'index']);
Route::get('/escape-rooms/{escapeRoom}', [EscapeRoomController::class, 'show']);
Route::get('/escape-rooms/{escapeRoom}/time-slots', [EscapeRoomController::class, 'timeSlots'])->name('time-slots');


//Protected Routes
Route::group(
    ['middleware' => ['auth:sanctum']],
    function () {
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::get('/bookings', [BookingController::class, 'index']);
        Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);
        Route::post('/logout', [AuthController::class, 'logout']);
    }
);

