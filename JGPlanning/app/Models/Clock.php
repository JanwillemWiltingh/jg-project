<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Clock extends Model
{
    use HasFactory;

    protected $table = 'clocker';

    protected $fillable = [
        'time',
        'start_time',
        'end_time',
        'date',
        'comment',
        'user_id'
    ];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reformatTime(string $name)
    {
        if($this[$name] != null) {
            return date('H:i', strtotime($this['start_time']));
        }
        return null;
    }

    public function timeWorkedToday(): string
    {
        if($this['end_time'] != null) {
            $first_time = Carbon::parse($this['start_time']);
            $second_time = Carbon::parse($this['end_time']);
            $time = $first_time->diffInSeconds($second_time);

            return CarbonInterval::seconds($time)->cascade()->forHumans();
        }
        return '';
    }
}
