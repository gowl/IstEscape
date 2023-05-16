<?php

namespace App\Models;

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
}
