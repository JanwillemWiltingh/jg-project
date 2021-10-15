<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $table = 'availability';

    const WEEK_DAYS = [
        '1' => 'Monday',
        '2' => 'Tuesday',
        '3' => 'Wednesday',
        '4' => 'Thursday',
        '5' => 'Friday',
        '6' => 'Saturday',
        '7' => 'Sunday'
    ];

    use HasFactory;

    protected $fillable = [
        'user_id',
        'start',
        'end',
        'from_home',
        'comment',
        'date',
        'weekdays'
    ];

    public $timestamps = false;
}