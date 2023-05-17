<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;


    protected $fillable = [
        'escape_room_id',
        'user_id',
        'begins_at',
        'ends_at',
        'discount'
    ];

    protected $casts = [
        'begins_at' => 'datetime',
        'ends_at' => 'datetime',
    ];
}
