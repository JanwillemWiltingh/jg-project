<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        'user_id',
        'deleted_at',
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
        $valid_ip = '88.218.7.239';

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

    /**
     * Calculates the time worked between a given start and end time in seconds
     *
     * @param $start_time
     * @param $end_time
     * @return float|int
     */
    public function timeWorkedInSeconds(string $start_time, $end_time)
    {
        if($end_time == null) {
            $end_time = Carbon::now()->addHours(self::ADD_HOURS)->toTimeString();
        }

        return Carbon::parse($end_time)->diffInSeconds($start_time);
    }

    /**
     * Calculates the time worked between a given start and end time in hours
     *
     * @param string $start_time
     * @param $end_time
     * @param int $decimal
     * @return string
     */
    public function timeWorkedInHours(string $start_time, $end_time, int $decimal=1): string
    {
        if($end_time == null) {
            $end_time = Carbon::now()->addHours(self::ADD_HOURS)->toTimeString();
        }

        $time = Carbon::parse($end_time)->diffInSeconds($start_time);
        return number_format($time / 3600, $decimal);
    }

    public function getUserData(string $field) {
        $user = $this->user()->first();
        return $user[$field];
    }
//    function roundToQuarterHour(string $start_time, $end_time) {
//        $minutes = date('i', strtotime($timestring));
//        return $minutes - ($minutes % 15);
//    }
    /**
     * @param string $role
     * @return bool
     * @throws Exception
     */
    public function allowedToEdit(string $role): bool
    {
        $user = Auth::user();
        return $user['role_id'] == Role::getRoleID($role) && !empty($this['end_time']);
    }
}
