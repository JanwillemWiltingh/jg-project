<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisabledDays extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'weekday',
        'start_week',
        'end_week'
    ];

    public $timestamps = false;
}
