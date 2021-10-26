<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooster extends Model
{
    protected $table = 'Rooster';

    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'from_home',
        'comment',
        'date',
        'weekdays',
        'start_week',
        'end_week',
        'rooster',
        'year'
    ];

    use HasFactory;
}
