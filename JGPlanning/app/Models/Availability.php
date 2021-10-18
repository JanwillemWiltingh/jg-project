<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $table = 'availability';

    const WEEK_DAYS = [
        '1' => 'Maandag',
        '2' => 'Dinsdag',
        '3' => 'Woensdag',
        '4' => 'Donderdag',
        '5' => 'Vrijdag',
        '6' => 'Zaterdag',
    ];

    use HasFactory;

    protected $fillable = [
        'user_id',
        'start',
        'end',
        'from_home',
        'comment',
        'date',
        'weekdays',
        'unavailable_days'
    ];

    public $timestamps = false;
}
