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
use Laravel\Sanctum\HasApiTokens;

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

    public function isClockedIn(): string
    {
        $latest = $this->clocks()->get()->last();
        if($latest['start'] == True) {
            return 'Ja';
        }
        return "Nee";
    }

    public function startTimeToday()
    {
        $first_clock = $this->clocks()->get()->first();
        return explode(' ', $first_clock['time'])[1];
    }
}
