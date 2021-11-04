<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
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

    /**
     * @return mixed|null
     */
    public function getRoosterFromToday() {
//        Take the week and day numbers from today
        $week_number = Carbon::now()->weekOfYear;
        $day_number = Carbon::now()->dayOfWeek;

//        Get the rooster where the day_number is the same as today
        $roosters = $this->roosters()->where('weekdays', $day_number)->get();

//        Loop through all the given roosters to find the rooster that fits for today
        foreach($roosters as $rooster) {
            if($rooster['start_week'] <= $week_number and $rooster['end_week'] >= $week_number) {
                return $rooster;
            }
        }

//        return null when there is no rooster from today
        return null;
    }

    public function getNextRooster() {
//        Get the rooster and week number of today
        $current_rooster = $this->getRoosterFromToday();
        $now_week_number = Carbon::now()->weekOfYear;

//        Make an empty collection to add all roosters to
        $collection = collect();

        if($current_rooster != null) {
//            Get all the rooster from the user
            $roosters = Rooster::all()->where('user_id', $this['id']);

//            Loop through all the roosters and only get the roosters with an ID higher then the current rooster
            foreach($roosters as $rooster) {
                if($rooster['id'] > $current_rooster['id']) {
                    $collection->push($rooster);
                }
            }

//            if any rooster has been added return the first one
            if($collection->count() > 0) {
                return $collection->first();
            }
        } else {
            $roosters = $this->roosters()->where('user_id', $this['id'])->where('start_week', '>=', $now_week_number)->get();

            if($roosters->count() > 0) {
                return $roosters->first();
            }
        }

        return null;
    }

    public function isCurrentUser(): string {
        if($this['id'] == Auth::id()) {
            return 'table-light';
        }

        return '';
    }

    /**
     * Returns the worked time in seconds from a given month
     *
     * @param $month
     * @return float|int|mixed
     */
    public function workedInAMonthInSeconds($month): int {
        $clocks = $this->clocks()->whereMonth('date', '=',$month)->get();
        $time = 0;

        if($clocks->count() > 0) {
            foreach($clocks as $clock) {
                if($clock['end_time'] == null){
                    $time = $time + Carbon::parse(Carbon::now()->addHours(2)->format('H:i:s'))->diffInSeconds(Carbon::parse($clock['start_time']));
                } else {
                    $time = $time + Carbon::parse($clock['end_time'])->diffInSeconds(Carbon::parse($clock['start_time']));
                }
            }
        }

        return $time;
    }

    /**
     * Returns the worked time in hours from a given month
     *
     * @param int $month
     * @param int $decimal_number
     * @return int
     */
    public function WorkedInAMonthInHours(int $month, int $decimal_number=1): float {
        $time = $this->workedInAMonthInSeconds($month);
        return number_format($time / 3600, $decimal_number);
    }

    /**
     * Returns the worked time formatted for humans by carbon in a month
     *
     * @param $month
     * @return string
     * @throws Exception
     */
    public function workedInAMonthForHumans($month): string
    {
        $time = $this->workedInAMonthInSeconds($month);
        return CarbonInterval::seconds($time)->cascade()->forHumans();
    }

    /**
     * Returns the worked time in seconds from a given week
     *
     * @param int $week
     * @return int
     */
    public function workedInAWeekInSeconds(int $week): int {
        $clocks = $this->clocks()->get();
        $time = 0;

        if($clocks->count() > 0) {
            foreach($clocks as $clock) {
                if(Carbon::parse($clock['date'])->weekOfYear == $week) {
                    if($clock['end_time'] == null){
                        $time = $time + Carbon::parse(Carbon::now()->addHours(2)->format('H:i:s'))->diffInSeconds(Carbon::parse($clock['start_time']));
                    } else {
                        $time = $time + Carbon::parse($clock['end_time'])->diffInSeconds(Carbon::parse($clock['start_time']));
                    }
                }
            }
        }

        return $time;
    }

    /**
     * Returns the worked time in hours from a given week
     *
     * @param int $week
     * @param int $decimal_number
     * @return int
     */
    public function workedInAWeekInHours(int $week, int $decimal_number=1): float {
        $time = $this->workedInAWeekInSeconds($week);
        return number_format($time / 3600, $decimal_number);
    }

    /**
     * Returns the worked time formatted for humans by carbon in a week
     *
     * @param $week
     * @return string
     * @throws Exception
     */
    public function workedInAWeekForHumans($week): string {
        $time = $this->workedInAWeekInSeconds($week);
        return CarbonInterval::seconds($time)->cascade()->forHumans();
    }

    public function workedInADayInSeconds(int $year, int $month, int $day): int {
        $date = Carbon::parse($year . '-' . $month . '-' . $day);
        $clocks = $this->clocks()->where('date', $date)->get();
        $time = 0;

        if ($clocks->count() > 0) {

            foreach ($clocks as $clock) {
                if ($clock['end_time'] == null) {
                    $time = $time + Carbon::parse(Carbon::now()->addHours(2)->format('H:i:s'))->diffInSeconds(Carbon::parse($clock['start_time']));
                } else {
                    $time = $time + Carbon::parse($clock['end_time'])->diffInSeconds(Carbon::parse($clock['start_time']));
                }
            }
        }
        return $time;
    }

    public function workedInADayInHours(int $year, int $month, int $day, int $decimal_number=0): float {
        $time = $this->plannedWorkADayInSeconds($year, $month, $day);
        return number_format($time / 3600, $decimal_number);
    }

    public function workedInADayForHumans(int $year, int $month, int $day): string {
        $time = $this->plannedWorkADayInSeconds($year, $month, $day);
        return CarbonInterval::seconds($time)->cascade()->forHumans();
    }

    /**
     * Returns the planned time in seconds from a given month
     *
     * @param int $year
     * @param int $month
     * @return int
     */
    public function plannedWorkAMonthInSeconds(int $year, int $month): int {
        //        Get all the roosters from the users from the given year
        $roosters = $this->roosters()->get();
        $time = 0;
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

            for ($i = 1; $i <= $days_of_month; $i++) {
                $date = new Carbon($year.'-'.$month.'-'.$i);
                $week_number = $date->weekOfYear;
                $day_of_week = $date->dayOfWeek;
                $current_rooster = $collection->where('start_week', '<=', $week_number)->where('end_week', '>=', $week_number)->where('weekdays', $day_of_week)->first();
                if($current_rooster != null) {
                    $time += Carbon::parse($current_rooster['end_time'])->diffInSeconds(Carbon::parse($current_rooster['start_time'])) - 1800;
                }
            }
        }
        return $time;
    }

    /**
     * Returns the planned time in hours from a given month
     *
     * @param int $year
     * @param int $month
     * @param int $decimal_number
     * @return int
     */
    public function plannedWorkAMonthInHours(int $year, int $month, int $decimal_number=1): float {
        $time = $this->plannedWorkAMonthInSeconds($year, $month);
        return number_format($time / 3600, $decimal_number);
    }

    /**
     * Returns the planned time formatted for humans by carbon in a month
     *
     * @param int $year
     * @param int $month
     * @return string
     * @throws Exception
     */
    public function plannedWorkAMonthForHumans(int $year, int $month): string {
        $time = $this->plannedWorkAMonthInSeconds($year, $month);
        return CarbonInterval::seconds($time)->cascade()->forHumans();
    }

    /**
     * Returns the planned time in seconds from a given week
     *
     * @param int $year
     * @param int $week
     * @return int
     */
    public function plannedWorkAWeekInSeconds(int $year, int $week): int {
        $roosters = $this->roosters()->get();
        $time = 0;

        if($roosters->count() > 0) {
//        Make a new collection
            $collection = collect();

            foreach($roosters as $rooster) {
                if($rooster['start_week'] <= $week and $rooster['end_week'] >= $week) {
                    $collection->push($rooster);
                }
            }

            foreach($collection as $day) {
                $time += Carbon::parse($day['end_time'])->diffInSeconds(Carbon::parse($day['start_time'])) - 1800;
            }
        }

        return $time;
    }

    /**
     * Returns the planned time in hours from a given week
     *
     * @param int $year
     * @param int $week
     * @param int $decimal_number
     * @return int
     */
    public function plannedWorkAWeekInHours(int $year, int $week, int $decimal_number=1): float {
        $time = $this->plannedWorkAWeekInSeconds($year, $week);
        return number_format($time / 3600, $decimal_number);
    }

    /**
     * Returns the planned time formatted for humans by carbon in a week
     *
     * @param int $year
     * @param int $week
     * @return string
     * @throws Exception
     */
    public function plannedWorkAWeekForHumans(int $year, int $week): string {
        $time = $this->plannedWorkAWeekInSeconds($year, $week);
        return CarbonInterval::seconds($time)->cascade()->forHumans();
    }

    public function plannedWorkADayInSeconds(int $year, int $week, int $day): int {
        $roosters = $this->roosters()->where('weekdays', $day)->get();
        $time = 0;
        foreach($roosters as $rooster) {
            if($rooster['start_week'] <= $week and $rooster['end_week'] >= $week) {
                $time = Carbon::parse($rooster['end_time'])->diffInSeconds(Carbon::parse($rooster['start_time'])) - 1800;
            }
        }
        return $time;
    }

    public function plannedWorkADayInHours(int $year, int $week, int $day, int $decimal_number=1): float {
        $time = $this->plannedWorkADayInSeconds($year, $week, $day);
        return number_format($time / 3600, $decimal_number);
    }

    public function plannedWorkADayForHumans(int $year, int $week, int $day): string {
        $time = $this->plannedWorkADayInSeconds($year, $week, $day);
        return CarbonInterval::seconds($time)->cascade()->forHumans();
    }

    /**
     * Returns the compared time worked and planned in seconds from a given week
     *
     * @param int $year
     * @param int $week
     * @return int
     */
    public function compareWeekWorkedInSeconds(int $year, int $week): int {
        return $this->workedInAWeekInSeconds($week) - $this->plannedWorkAWeekInSeconds($year, $week);
    }

    /**
     * Returns the compared time worked and planned in hours from a given week
     *
     * @param int $year
     * @param int $week
     * @param int $decimal_number
     * @return int
     */
    public function compareWeekWorkedInHours(int $year, int $week, int $decimal_number=0): float {
        $time = $this->compareWeekWorkedInSeconds($year, $week);
        return number_format($time / 3600, $decimal_number);
    }

    /**
     * Returns the compared time worked and planned formatted for humans by carbon in a week
     *
     * @param int $year
     * @param int $month
     * @return string
     * @throws Exception
     */
    public function compareWeekWorkedForHumans(int $year, int $month): string {
        $time = $this->compareWeekWorkedInSeconds($year, $month);
        return CarbonInterval::seconds($time)->cascade()->forHumans();
    }

    /**
     * Returns the compared time worked and planned in seconds from a given month
     *
     * @param int $year
     * @param int $month
     * @return int
     */
    public function compareMonthWorkedInSeconds(int $year, int $month): int {
        return $this->workedInAMonthInSeconds($month) - $this->plannedWorkAMonthInSeconds($year, $month);
    }

    /**
     * Returns the compared time worked and planned in hours from a given month
     *
     * @param int $year
     * @param int $month
     * @param int $decimal_number
     * @return int
     */
    public function compareMonthWorkedInHours(int $year, int $month, int $decimal_number=1): float {
        $time = $this->compareMonthWorkedInSeconds($year, $month);
        return number_format($time / 3600, $decimal_number);
    }

    /**
     * Returns the compared time worked and planned formatted for humans by carbon in a month
     *
     * @param int $year
     * @param int $month
     * @return string
     * @throws Exception
     */
    public function compareMonthWorkedForHumans(int $year, int $month): string {
        $time = $this->compareMonthWorkedInSeconds($year, $month);
        return CarbonInterval::seconds($time)->cascade()->forHumans();
    }
}
