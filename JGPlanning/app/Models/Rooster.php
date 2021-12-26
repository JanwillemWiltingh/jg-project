<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rooster extends Model
{
    protected $table = 'rooster';

    public $timestamps = false;

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
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }
}
