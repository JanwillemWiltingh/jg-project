<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Clock;
use App\Models\Role;
use App\Models\Rooster;
use App\Models\User;
use App\Services\CalendarService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoosterAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */

//  Functie om gebruikers hun roosters te zien.
    public function index_rooster()
    {
        $roles = Role::$roles;
        $users = User::where('role_id', $roles['employee'])->get();

        return view('admin.availability.calender.table', compact(
            'users',
        ));
    }

//  Functie voor admins om naar gebruikers hun rooster te kijken.
    public function user_rooster(CalendarService $calendarService, $user)
    {
        $user_info = User::find($user);
        $isRooster    = true;
        $availability = Rooster::where('user_id', $user)->get();
        $weekDays     = Availability::WEEK_DAYS;
        $calendarData = $calendarService->generateCalendarData($weekDays, $user_info->id, $isRooster);

        return view('admin.rooster.index', compact(
            'weekDays',
            'calendarData',
            'availability',
            'user',
            'user_info'
        ));
    }

//  Functie om de array met de disabled dagen te sturen naar de database.
    public function push_days($user, Request $request)
    {
        $validated = $request->validate([
            'data' => ['required', 'array']
        ]);



        for ($i = 1; $i < 6; $i++)
        {
            if ($validated['data'][$i - 1])
            {
                if(Rooster::all()->where('user_id', $user)->where('weekdays', $i)->first())
                {
                    $updaterooster = Rooster::where('weekdays', $i)->where('user_id', $user)->update([
                        'disabled' => true
                    ]);
                }
            }
            else
            {
                $updaterooster = Rooster::where('weekdays', $i)->where('user_id', $user)->update([
                    'disabled' => false
                ]);
            }
        }

        $update = User::where('id', $user)->update([
            'unavailable_days' => $validated['data']
        ]);

        return redirect()->back();
    }
}
