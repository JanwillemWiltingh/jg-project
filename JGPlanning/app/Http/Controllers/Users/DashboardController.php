<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Clock;
use App\Models\Role;
use App\Models\Rooster;
use App\Models\User;
use App\Services\TimeService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $user_session = Auth::user();
        $now = Carbon::now();
        $enable_time = null;
        //users van vandaag ophalane
        $day = Carbon::now()->format('Y-m-d');
        $users = User::all()
            ->where('deleted_at', '=', null)
            ->where('role_id', 3);
        $clocks = Clock::all();
        $roles = Role::all();

        $clocks = $user_session->clocks()->get();

        if($clocks->count() > 0) {
            $clock_today = $clocks->where('date', Carbon::now()->format('Y-m-d'));
            if($clock_today->count() > 0) {
                $clock = $clock_today->last();
                if($clock['end_time'] == null) {
                    $time = Carbon::parse($clock['start_time'])->addMinutes(15)->format('H:i');
                    if(!Carbon::parse($time)->isPast()) {
                        $enable_time = $time;
                    }
                }
            }
        }
        $clocks = Clock::all();
            return view('dashboard.index')->with([
                'start' => $user_session->isClockedIn(),
                'user_session' => $user_session,
                'now' => $now,
                'allowed' => Clock::isIPCorrect($request),
                'enable_time' => $enable_time,
                'users' => $users,
                'day' => $day,
                'roles' => $roles,
                'clocks' => $clocks,
            ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function clock(Request $request): RedirectResponse
    {
        $timeservice = new TimeService();
        $time_final = [];

        //  If the IP is correct let the user clock in
        if(Clock::isIPCorrect($request)) {
            $validated = $request->validate([
                'opmerking' => ['nullable', 'string', 'max:150'],
            ]);

            //  Get the logged-in user
            $user = Auth::user();

            //  Get all the clocks from today
            $clocks = Clock::all()->where('user_id', $user['id'])->where('date', Carbon::now()->toDateString());

            //  Round the current time to quarters
            $now = Carbon::now()->addHours(Clock::ADD_HOURS);
            $time = $timeservice->roundTime($now, 15);
            if($clocks->count() == 0) {
                //  When there are no clocks add a new one
                Clock::create([
                    'comment' => $validated['opmerking'],
                    'user_id' => $user['id'],
                    'start_time' => $time,
                    'end_time' => null,
                    'date' => $now->toDateString()
                ]);
            } else {
                if($clocks->last()['end_time'] != null) {
                    //  If the last clock has an already filled in end time, make a new one
                    Clock::create([
                        'comment' => $validated['opmerking'],
                        'user_id' => $user['id'],
                        'start_time' => $time,
                        'end_time' => null,
                        'date' => $now->toDateString()
                    ]);
                } else {
                    //  Update the clock end time
                    foreach ($clocks as $c)
                    {
                        if ($c->end_time == null)
                        {
                            array_push($time_final,(Ceil(Carbon::parse($c->start_time)->diffInMinutes(Carbon::parse($time)) / 60 / .25)) * .25 );
                        }
                        else
                        {
                            array_push($time_final,(Ceil(Carbon::parse($c->start_time)->diffInMinutes(Carbon::parse($c->end_time)) / 60 / .25)) * .25 );
                        }
                    }

                    if (array_sum($time_final) > 5)
                    {
                        $clocks->last()->update(['end_time' => Carbon::parse($time)->subMinutes(30)->format('H:i')]);
                    }
                    else
                    {
                        $clocks->last()->update(['end_time' => $time]);
                    }
                }
            }
        }
//        dd('asa');
        return redirect()->back();
    }
}
