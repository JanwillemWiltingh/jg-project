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
}
