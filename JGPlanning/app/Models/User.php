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
        'name',
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


    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function clocks(): HasMany
    {
        return $this->hasMany(Clock::class);
    }

    public function roosters(): HasMany
    {
        return $this->hasMany(Rooster::class);
    }

    public function hasRole($role): bool
    {
        return $this->role()->get()->unique()->where('name', $role)->first() != null;
    }

    public function isClockedIn(): bool
    {
        $last_clock = Clock::all()->where('user_id', $this['id'])->where('date', Carbon::now()->toDateString())->last();
        if($last_clock == null) {
            return False;
        } else if($last_clock['end_time'] === null) {
            return True;
        } else {
            return False;
        }
    }

    public function startTimeToday()
    {
        $first_clock = $this->clocks()->get()->first();
        return explode(' ', $first_clock['time'])[1];
    }

    public function workedInAMonth($month): array
    {
        $clocks = $this->clocks()->whereMonth('date', '=','10')->get();
        $time = 0;

        if($clocks->count() > 0) {
            foreach($clocks as $clock) {
                $time = $time + Carbon::parse($clock['end_time'])->diffInSeconds(Carbon::parse($clock['start_time']));
            }

            return [CarbonInterval::seconds($time)->cascade()->forHumans(), $time];
        }

        return ['-', 0];
    }

    public function isCurrentUser(): string
    {
        if($this['id'] == Auth::id()) {
            return 'table-light';
        }

        return '';
    }

    public function plannedWorkAMonth($year, $month): array
    {
//        Get all the roosters from the users from the given year
        $roosters = $this->roosters()->get();

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
//            print($i.' ');
        }

        if($this['id'] == 2) {
            dd($collection);
        }

        return ['-', 0];
    }
}
