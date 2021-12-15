<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooster extends Model
{
    protected $table = 'rooster';


    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'comment',
        'from_home',
        'weekdays',
        'created_at',
        'updated_at',
        'start_week',
        'end_week',
        'disabled',
        'start_year',
        'end_year'
    ];

    use HasFactory;
}
