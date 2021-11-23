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
        'end_week',
        'start_year',
        'end_year',
        'by_admin'
    ];

    public $timestamps = false;
}
