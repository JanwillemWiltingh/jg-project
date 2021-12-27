<?php

namespace App\Models;

use App\Services\TimeService;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Exception;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\False_;
use Kyslik\ColumnSortable\Sortable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Sortable;

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
        'phone_number',
    ];
    public $sortable = [
        'firstname',
        'lastname',
        'email',
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
        $roosters = $this->roosters()->where('weekday', $day_number)->get();
//        Loop through all the given roosters to find the rooster that fits for today
        foreach($roosters as $rooster) {
            if($rooster['week'] = $week_number ) {
                if (DisabledDays::all()->where('user_id', $this->id)->where('start_week', $week_number)->where('start_year', Carbon::now()->year)->where('weekday', $day_number)->count() == 1)
                {
                    return null;
                }
                else
                {
                    return $rooster;
                }
            }
        }

//        return null when there is no rooster from today
        return null;
    }

    public function getNextRooster() {
        $date = Carbon::now();
//        Get the rooster and week number of today
        $current_rooster = self::getRoosterFromToday();
        $now_week_number = Carbon::now()->weekOfYear;
        $now_year_number = Carbon::now()->year;

        if (Carbon::now()->dayOfWeek < 6)
        {
            $now_day_number = Carbon::now()->dayOfWeek + 1;
        }
        else
        {
            $now_day_number = 1;
        }

        $disable = DisabledDays::all()->where('user_id', $this['id'])->where('start_week', '>=', $now_week_number)->where('start_year', $now_year_number)->where('weekday', $now_day_number);

        if ($disable->count() > 0)
        {
            return null;
        }

//        Make an empty collection to add all roosters to
        $collection = collect();

        if($current_rooster != null) {
//            Get all the rooster from the user
            $roosters = Rooster::all()->where('user_id', $this['id']);
//            Loop through all the roosters and disabled days and only get the roosters with an ID higher then the current rooster


            foreach($roosters as $rooster)
            {
                if ($rooster['id'] > $current_rooster['id'])
                {
                    $collection->push($rooster);
                }
            }

//            if any rooster has been added return the first one
            if($collection->count() > 0) {
                return $collection->first();
            }
        } else {
            $roosters = $this->roosters()->where('user_id', $this['id'])->where('week' , $now_week_number)->where('start_year', $now_year_number)->get();


            if($roosters->count() > 0) {
                return $roosters->first();
            }
        }

        return null;
    }

    public function isCurrentUser(): string {
        if($this['id'] == Auth::id()) {
            return 'table-secondary';
        }

        return '';
    }

    public function calculateTime(Collection $clocks): int {
        $time = 0;

        if($clocks->count() > 0) {
            foreach($clocks as $clock) {
                if($clock['end_time'] == null){
                    $temporary_time = Carbon::parse(Carbon::now()->addHours(Clock::ADD_HOURS)->format('H:i:s'))->diffInSeconds(Carbon::parse($clock['start_time']));
                } else {
                    $temporary_time = Carbon::parse($clock['end_time'])->diffInSeconds(Carbon::parse($clock['start_time']));
                }
                $time += $temporary_time;
            }
        }

        return $time;
    }

    /**
     * Returns the worked time in seconds from a given month
     *
     * @param $month
     * @return float|int|mixed
     */
    public function workedInAMonthInSeconds($month): int {
        $clocks = $this->clocks()->whereMonth('date', '=',$month)->get();
        return $this->calculateTime($clocks);
    }

    /**
     * Returns the worked time in hours from a given month
     *
     * @param int $month
     * @param int $decimal_number
     * @return float
     */
    public function WorkedInAMonthInHours(int $month, int $decimal_number=1): float {
            $time = $this->workedInAMonthInSeconds($month);
//            if ($time /3600 < 0.25)
//            {
//                $final_time = 0;
//            }
//            else
//            {
                $final_time = (Ceil($time /3600 / .25)) * .25;
//            }

        if ($final_time > 5)
        {
            return number_format($final_time, $decimal_number - .5);
        }
        else
        {
            return number_format($final_time, $decimal_number);
        }
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
                        $temporary_time = Carbon::parse(Carbon::now()->addHours(Clock::ADD_HOURS)->format('H:i:s'))->diffInSeconds(Carbon::parse($clock['start_time']));

                    } else {
                        $temporary_time = Carbon::parse($clock['end_time'])->diffInSeconds(Carbon::parse($clock['start_time']));

                    }

                    if($temporary_time >= 14400) {
                        $temporary_time -= 1800;
                    }
                    $time += $temporary_time;
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
     * @return float
     */
    public function workedInAWeekInHours(int $week, int $decimal_number=1): float {
        $time = $this->workedInAWeekInSeconds($week);
//        if ($time /3600 < 0.25)
//        {
//            $final_time = 0;
//        }
//        else
//        {
            $final_time = (Ceil($time /3600 / .25)) * .25;
//        }

        if ($final_time > 5)
        {
            return number_format($final_time, $decimal_number - .5);
        }
        else
        {
            return number_format($final_time, $decimal_number);
        }
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

    /**
     * Returns the worked time in seconds from a given day
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return int
     */
    public function workedInADayInSeconds(int $year, int $month, int $day): int {
        $date = Carbon::parse($year.'-'.$month.'-'.$day);
        $clocks = $this->clocks()->where('date', $date)->get();
        return $this->calculateTime($clocks);
    }

    /**
     * Returns the worked time in hours from a given day
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $decimal_number
     * @return float
     */
    public function workedInADayInHours(int $year, int $month, int $day, int $decimal_number=0): float {
        $time = $this->workedInADayInSeconds($year, $month, $day);
        $final_time = (Ceil($time /3600 / .25)) * .25;
        return number_format($final_time, $decimal_number);
    }

    /**
     * Returns the worked time formatted for humans by carbon from a given day
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return string
     * @throws Exception
     */
    public function workedInADayForHumans(int $year, int $month, int $day): string {
        $time = $this->workedInADayInSeconds($year, $month, $day);
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

            $start_date = $year.'.'.$first_week;
            $end_date = $year.'.'.$last_week;

//        Make a new collection
            $collection = collect();

//        Filter all roosters
            foreach ($roosters as $rooster) {
                $in_range = false;

                $rooster_start_date = $rooster['start_year'].'.'.$rooster['week'];

                if($rooster_start_date = $start_date ) {
                    $in_range = true;
                }

                if($rooster_start_date = $end_date) {
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
                $current_rooster = $collection->where('week', $week_number)->where('weekday', $day_of_week)->first();

                if($current_rooster != null) {
                    $temporary_time = Carbon::parse($current_rooster['end_time'])->diffInSeconds(Carbon::parse($current_rooster['start_time']));

                    if($temporary_time >= 14400) {
                        $temporary_time -= 1800;
                    }
                    $time += $temporary_time;
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
     * @return float
     */
    public function plannedWorkAMonthInHours(int $year, int $month, int $decimal_number=1): float {
        $time = $this->plannedWorkAMonthInSeconds($year, $month);
        $final_time = (Ceil($time /3600 / .25)) * .25;
        if ($final_time >= 5)
        {
            return number_format($final_time - .5, $decimal_number);
        }
        else
        {
            return number_format($final_time, $decimal_number);
        }
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
    public function plannedWorkAWeekInSeconds(int $year, int $week, int $id): int {
        $roosters = Rooster::all()->where('user_id', $id)->where('start_year',  $year)->where('week', $week);
        $disabled = DisabledDays::all()->where('user_id', $id);
        $time = 0;

        if($roosters->count() > 0) {
//        Make a new collection
            $collection = collect();

            foreach($roosters as $rooster) {
                if ($disabled->where('start_year',  $rooster['start_year'])->where('start_week', $rooster['week'])->where('weekday', $rooster['weekday'])->count() == 0)
                {
                    $collection->push($rooster);
                }
            }

            foreach($collection as $day) {
                $temporary_time = Carbon::parse($day['end_time'])->diffInSeconds(Carbon::parse($day['start_time']));

                if($temporary_time >= 14400) {
                    $temporary_time -= 1800;
                }
                $time += $temporary_time;

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
     * @return float
     */
    public function plannedWorkAWeekInHours(int $year, int $week, int $id, int $decimal_number=1): float {
        $time = $this->plannedWorkAWeekInSeconds($year, $week, $id);
        $final_time = (Ceil($time /3600 / .25)) * .25;
        if ($final_time >= 5)
        {
            return number_format($final_time - .5, $decimal_number);
        }
        else
        {
            return number_format($final_time, $decimal_number);
        }
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

    /**
     * Returns the planned time in seconds from a given day
     *
     * @param int $year
     * @param int $week
     * @param int $day
     * @return int
     */
    public function plannedWorkADayInSeconds(int $year, int $week, int $day): int {
        $new_date = new Carbon();
        $date = $new_date->setISODate($year, $week, $day);

        $roosters = $this->roosters()->where('weekday', $date->dayOfWeek)->get();
        $time = 0;
        foreach($roosters as $rooster) {
            if($rooster['week'] = $week ) {
                $time = Carbon::parse($rooster['end_time'])->diffInSeconds(Carbon::parse($rooster['start_time']));

                if($time >= 14400) {
                    $time -= 1800;
                }
            }
        }

        return $time;
    }

    /**
     * Returns the planned time in hours from a given day
     *
     * @param int $year
     * @param int $week
     * @param int $day
     * @param int $decimal_number
     * @return float
     */
    public function plannedWorkADayInHours(int $year, int $week, int $day, int $decimal_number=1): float {
        $time = $this->plannedWorkADayInSeconds($year, $week, $day);
        $final_time = (Ceil($time /3600 / .25)) * .25;
        if ($final_time >= 5)
        {
            return number_format($final_time - .5, $decimal_number);
        }
        else
        {
            return number_format($final_time, $decimal_number);
        }
    }

    /**
     * Returns the planned time formatted for humans by carbon for a given day
     *
     * @param int $year
     * @param int $week
     * @param int $day
     * @return string
     * @throws Exception
     */
    public function plannedWorkADayForHumans(int $year, int $week, int $day): string {
        $time = $this->plannedWorkADayInSeconds($year, $week, $day);
        return CarbonInterval::seconds($time)->cascade()->forHumans();
    }

    public function compareDayWorkedInSeconds(int $year, int $month, int $day): int {
        $date = new Carbon($year.'-'.$month.'-'.$day);
        return $this->workedInADayInSeconds($year, $month, $day) - $this->plannedWorkADayInSeconds($year, $date->weekOfYear, $day);
    }

    public function compareDayWorkedInHours(int $year, int $month, int $day, int $decimal_number=1): float {
        $time = $this->compareDayWorkedInSeconds($year, $month, $day);
        $final_time = (Ceil($time /3600 / .25)) * .25;
        if ($final_time >= 5)
        {
            return number_format($final_time - .5, $decimal_number);
        }
        else
        {
            return number_format($final_time, $decimal_number);
        }
    }

    public function compareDayWorkedForHumans(int $year, int $month, int $day): string {
        $time = $this->compareDayWorkedInSeconds($year, $month, $day);
        return CarbonInterval::seconds($time)->cascade()->forHumans();
    }

    /**
     * Returns the compared time worked and planned in seconds from a given week
     *
     * @param int $year
     * @param int $week
     * @return int
     */
    public function compareWeekWorkedInSeconds(int $year, int $week, int $id): int {
        return $this->workedInAWeekInSeconds($week) - $this->plannedWorkAWeekInSeconds($year, $week, $id);
    }

    /**
     * Returns the compared time worked and planned in hours from a given week
     *
     * @param int $year
     * @param int $week
     * @param int $decimal_number
     * @return float
     */
    public function compareWeekWorkedInHours(int $year, int $week, int $id, int $decimal_number=1): float {
        $time = $this->compareWeekWorkedInSeconds($year, $week, $id);
        $final_time = (Ceil($time /3600 / .25)) * .25;
        if ($final_time >= 5)
        {
            return number_format($final_time - .5, $decimal_number);
        }
        else
        {
            return number_format($final_time, $decimal_number);
        }
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
     * @return float
     */
    public function compareMonthWorkedInHours(int $year, int $month, int $decimal_number=1): float {
        $time = $this->compareMonthWorkedInSeconds($year, $month);
        $final_time = (Ceil($time /3600 / .25)) * .25;

        return number_format($final_time, $decimal_number);
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

    public function fieldColorForDay(int $year, int $month, int $day): string {
        if($this->compareDayWorkedInSeconds($year, $month, $day)) {
            return "table-danger";
        } else {
            return "table-success";
        }
    }

    public function fieldColorForWeek($year, $weeks, $id): string {
        if($this->compareWeekWorkedInSeconds($year, str_replace('W', '',explode('-', $weeks)[1]), $id) < 0)
            return "table-danger";
        else{
            return "table-success";
        }
    }

    public function fieldColorForMonth($year, $month): string {
        if($this->compareMonthWorkedInSeconds($year, explode('-', $month)[1]) < 0)
            return "table-danger";
        else {
            return "table-success";
        }
    }

    public function getStartTime($date): ?string
    {
        //  Get all the clocks from this user
        $clocks = $this->clocks()->get();

        //  Check if there are any clocks for this user
        if($clocks->count() > 0) {
            //  Get all the clocks for the given date
            $date_clock = $clocks->where('date', $date->format('Y-m-d'));

            //  Check if there are any clocks for the given day
            if($date_clock->count() > 0) {
                //  Get the first clock
                $first = $date_clock->first();

                //  Return the start time
                return Carbon::parse($first['start_time'])->format('H:i');
            }
        }
        return null;
    }

    /**
     * Function for returning the End Time for a given day
     *
     * @param $date
     * @return string|null
     */
    public function getEndTime($date): ?string
    {
        //  Take all clocks from this user
        $clocks = $this->clocks()->get();

        //  Check if there are any clocks for this user
        if($clocks->count() > 0) {
            //  Get all the clocks from the given date
            $date_clock = $clocks->where('date', $date->format('Y-m-d'));

            //  Check if there are any clocks from the given day
            if($date_clock->count() > 0) {
                //  Get the last clock from the given day
                $last = $date_clock->last();

                if($last['end_time'] == null) {
                    //  If there is no end time take the current time
                    return Carbon::now()->addHours(Clock::ADD_HOURS)->format('H:i');
                } else {
                    //  Return the end time
                    if ($date_clock->first()->end_time) {
                        $last = $date_clock->last();
                        return Carbon::parse($last['end_time'])->format('H:i');
                    } else {
                        return null;
                    }
                }
            }
        }
        //  Return null if no clocks are given
        return null;
    }

    public function checkIfRoosterIsSolidified($date): bool
    {
        $date2 = Carbon::now();
        $rooster = Rooster::all()
            ->where('user_id', $this->id)
            ->where('finalized', true);

        foreach ($rooster as $r)
        {
            $final_date = $date2
                ->setISODate($r->start_year, $r->week)
                ->addDays($r->weekday - 1);
            if ($final_date->isCurrentWeek() || $final_date->isNextWeek())
            {
                return true;
            }
        }
        return false;
    }

    public function getClockComment($date)
    {
        $clock = $this->clocks()->where('date', $date)->first();

        if ($clock->comment)
        {
            return $clock->comment;
        }
        else
        {
            return "Geen opmerking";
        }
    }
}
