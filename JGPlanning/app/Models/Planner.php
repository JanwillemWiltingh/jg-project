<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Planner extends Model
{
    use HasFactory;

    protected $table = 'planner';

    protected $fillable = [
        'user_id',
        'start',
        'end',
    ];

    public $timestamps = false;

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
