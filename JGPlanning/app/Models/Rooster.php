<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooster extends Model
{
    protected $table = 'Rooster';

    protected $fillable = [
        'user_id',
        'start',
        'end',
        'from_home',
        'comment',
        'date',
        'weekdays'
    ];

    use HasFactory;
}
