<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use phpDocumentor\Reflection\Types\False_;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'email',
        'password',
        'role_id',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public $timestamps = true;


    public function role(): HasOne {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function clocks(): HasMany {
        return $this->hasMany(Clock::class);
    }

    public function roosters(): HasMany {
        return $this->hasMany(Rooster::class);
    }

    public function hasRole($role): bool {
        return $this->role()->get()->unique()->where('firstname', $role)->first() != null;
    }

    public function isClockedIn(): bool {
        $last_clock = Clock::all()->where('user_id', $this['id'])->where('date', Carbon::now()->toDateString())->last();
        if($last_clock == null) {
            return False;
        } else if($last_clock['end_time'] === null) {
            return True;
        } else {
            return False;
        }
    }

    public function startTimeToday() {
        $first_clock = $this->clocks()->get()->first();
        return explode(' ', $first_clock['time'])[1];
    }

    public function getRoosterFromToday() {
        $week_number = Carbon::now()->weekOfYear;
        $day_number = Carbon::now()->dayOfWeek;

        $roosters = $this->roosters()->where('weekdays', $day_number)->get();

        foreach($roosters as $rooster) {
            if($rooster['start_week'] <= $week_number and $rooster['end_week'] >= $week_number) {
                return $rooster;
            }
        }

        return ['start_time' => '00:00', 'end_time' => '00:00'];
    }

    public function getNextRooster() {
        $current_rooster = Auth::user()->getRoosterFromToday();
        $roosters = $this->roosters()->get();
        $next = $roosters->where('id', $current_rooster['id'] + 1)->first();
        return $next;
    }

    public function isCurrentUser(): string {
        if($this['id'] == Auth::id()) {
            return 'table-light';
        }

        return '';
    }

    public function workedInAMonth($month): array {
        $clocks = $this->clocks()->whereMonth('date', '=',$month)->get();
        $time = 0;

        if($clocks->count() > 0) {
            foreach($clocks as $clock) {
                $time = $time + Carbon::parse($clock['end_time'])->diffInSeconds(Carbon::parse($clock['start_time']));
            }

//            $weeks = floor($time / 604800);
//            $remainder = $time - ($weeks * 604800);
//
//            $days = floor($remainder / 86400);
//            $remainder = $remainder - ($days * 86400);
//
//            $hours = floor($remainder / 3600);
//            $remainder = $remainder - ($hours * 3600);
//
//            $minutes = floor($remainder / 60);
//            $seconds = $remainder - ($minutes * 60);

            return [
                CarbonInterval::seconds($time)->cascade()->forHumans(),
                $time,
            ];
        }

        return ['-', 0];
    }

    public function workedInAWeek($week): array {
        $clocks = $this->clocks()->get();

        if($clocks->count() > 0) {
            $time = 0;
            foreach($clocks as $clock) {
                if(Carbon::parse($clock['date'])->weekOfYear == $week) {
                    $time = $time + Carbon::parse($clock['end_time'])->diffInSeconds(Carbon::parse($clock['start_time']));
                }
            }

            return [
                CarbonInterval::seconds($time)->cascade()->forHumans(),
                $time,
            ];
        }

        return ['-', 0];
    }

    public function workedInADay($year, $month, $day) {
        $date = Carbon::parse($year.'-'.$month.'-'.$day);
        $clocks = $this->clocks()->where('date', $date)->get();

        if($clocks->count() > 0) {
            $time = 0;
            foreach($clocks as $clock) {
                $time = $time + Carbon::parse($clock['end_time'])->diffInSeconds(Carbon::parse($clock['start_time']));
            }

            return [
                CarbonInterval::seconds($time)->cascade()->forHumans(),
                $time,
            ];
        }

        return ['-', 0];
    }

    public function plannedWorkAMonth($year, $month): array {
//        Get all the roosters from the users from the given year
        $roosters = $this->roosters()->get();
        if($roosters->count() > 0) {
            //        Get the first day of the give month and year
            $first_day_of_month = new Carbon($year.'-'.$month.'-01');

//        using the newly made day get the amount of days in this month
            $days_of_month = $first_day_of_month->daysInMonth;

//        using the days in this month to get the last day of the month
            $last_day_of_month = new Carbon($year.'-'.$month.'-'.$days_of_month);

//        us the first and last day of the month to get the first and last week number of this month
            $first_week = $first_day_of_month->weekOfYear;
            $last_week = $last_day_of_month->weekOfYear;

//        Make a new collection
            $collection = collect();

//        Filter all roosters
            foreach ($roosters as $rooster) {
                $in_range = false;

                if($rooster['year'].'.'.$rooster['start_week'] >= $year.'.'.$first_week && $rooster['year'].'.'.$rooster['start_week'] <= $year.'.'.$last_week) {
                    $in_range = true;
                }

                if($rooster['year'].'.'.$rooster['end_week'] >= $year.'.'.$first_week && $rooster['year'].'.'.$rooster['end_week'] <= $year.'.'.$last_week) {
                    $in_range = true;
                }

                if($in_range) {
                    $collection->push($rooster);
                }
            }
            $time = 0;
            for ($i = 1; $i <= $days_of_month; $i++) {
                $date = new Carbon($year.'-'.$month.'-'.$i);
                $week_number = $date->weekOfYear;
                $day_of_week = $date->dayOfWeek;
                $current_rooster = $collection->where('start_week', '<=', $week_number)->where('end_week', '>=', $week_number)->where('weekdays', $day_of_week)->first();
                if($current_rooster != null) {
                    $time += Carbon::parse($current_rooster['end_time'])->diffInSeconds(Carbon::parse($current_rooster['start_time']));
                }
            }
            return [CarbonInterval::seconds($time)->cascade()->forHumans(), $time];
        }
        return ['-', 0];
    }

    public function plannedWorkAWeek($year, $week): array {
        $roosters = $this->roosters()->get();

        if($roosters->count() > 0) {
            //        Make a new collection
            $collection = collect();

            foreach($roosters as $rooster) {
                if($rooster['start_week'] <= $week and $rooster['end_week'] >= $week) {
                    $collection->push($rooster);
                }
            }

            $time = 0;
            foreach($collection as $day) {
                $time += Carbon::parse($day['end_time'])->diffInSeconds(Carbon::parse($day['start_time']));
            }
            return [CarbonInterval::seconds($time)->cascade()->forHumans(), $time];
        }

        return ['-', 0];
    }

    public function plannedWorkADay($year, $week, $day): array {
        $roosters = $this->roosters()->where('weekdays', $day)->get();
        foreach($roosters as $rooster) {
            if($rooster['start_week'] <= $week and $rooster['end_week'] >= $week) {
                $time = Carbon::parse($rooster['end_time'])->diffInSeconds(Carbon::parse($rooster['start_time']));

                return [CarbonInterval::seconds($time)->cascade()->forHumans(), $time];
            }
        }
        return ['-', 0];
    }

    public function compareWeekWorked($year, $week): array {
        $difference = $this->workedInAWeek($week)[1] - $this->plannedWorkAWeek($year, $week)[1];

        return [CarbonInterval::seconds($difference)->cascade()->forHumans(), $difference];
    }

    public function compareMonthWorked($year, $month): array {
        $difference = $this->workedInAMonth($month)[1] - $this->plannedWorkAMonth($year, $month)[1];

        return [CarbonInterval::seconds($difference)->cascade()->forHumans(), $difference];
    }
}
