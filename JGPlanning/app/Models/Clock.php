<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Collection;

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

//    Een constant zodat overal dezelfde tijd wordt toegevoegt
    public const ADD_HOURS = 1;

    /**
     * Function for checking if the ip the user is on is correct
     *
     * @param Request $request
     * @return bool
     */
    public static function isIPCorrect(Request $request): bool {
        $ip = $request->ip();
        $valid_ip = '192.168.1.';

        return str_contains($ip, $valid_ip);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reformatTime(string $name)
    {
        if($this[$name] != null) {
            return date('H:i', strtotime($this[$name]));
        }
        return null;
    }

    public function timeWorkedToday(bool $break): string
    {
        if($this['end_time'] != null) {
            $first_time = Carbon::parse($this['start_time']);
            $second_time = Carbon::parse($this['end_time']);

            if($break) {
                $second_time = Carbon::parse($this['end_time'])->subMinutes(30);
            }

            $time = $first_time->diffInSeconds($second_time);

            return CarbonInterval::seconds($time)->cascade()->forHumans();
        }
        return '-';
    }
}
