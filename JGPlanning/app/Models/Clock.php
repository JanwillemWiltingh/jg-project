<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Clock extends Model
{
    use HasFactory;

    protected $table = 'clocker';

    protected $fillable = [
        'time',
        'start',
        'comment',
        'user_id'
    ];

    public $timestamps = false;

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
