<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Clock;
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
        $user = Auth::user();
        $now = Carbon::now();
        $enable_time = null;

        $clocks = $user->clocks()->get();

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
        return view('dashboard.index')->with(['start' => $user->isClockedIn(), 'user' => $user, 'now' => $now, 'allowed' => Clock::isIPCorrect($request), 'enable_time' => $enable_time]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function clock(Request $request): RedirectResponse
    {
        if(Clock::isIPCorrect($request)) {
            $validated = $request->validate([
                'comment' => ['nullable', 'string', 'max:150'],
            ]);

            $user = Auth::user();
            $clocks = Clock::all()->where('user_id', $user['id'])->where('date', Carbon::now()->toDateString());
            $start_time = Carbon::parse('08:30:00');
            $end_time = Carbon::parse('17:30:00');

            //  When someone clocks in before or after working hours give an error message and don't clock them in
//            if(!$user->isClockedIn()) {
//                if($start_time->isFuture()) {
//                    return redirect()->back()->with(['message'=> ['message' => 'Er kan pas vanaf 08:30 ingeklokt worden', 'type' => 'danger']]);
//                } elseif ($end_time->isPast()) {
//                    return redirect()->back()->with(['message' => ['message' => 'Werktijden zijn voorbij, er kan niet meer ingeklokt worden', 'type' => 'danger']]);
//                }
//            }

            //  Round the current time to quarters
            $now = Carbon::now()->addHours(Clock::ADD_HOURS);
            $hours = $now->format('H');
            $minutes = $now->format('i');
            $rounded_minutes = round($minutes / 15) * 15;

            if($rounded_minutes == 60) {
                $time = (intval($hours) + 1).':00';
            } else {
                $time = Carbon::parse($hours.':'.$rounded_minutes)->format('H:i');
            }

            $time = $now->format('H:i');

            if($clocks->count() == 0) {
                //  When there are no clocks add a new one
                Clock::create([
                    'comment' => $validated['comment'],
                    'user_id' => $user['id'],
                    'start_time' => $time,
                    'end_time' => null,
                    'date' => Carbon::now()->toDateString()
                ]);
            } else {
                if($clocks->last()['end_time'] != null) {
                    //  If the last clock has an already filled in end time, make a new one
                    Clock::create([
                        'comment' => $validated['comment'],
                        'user_id' => $user['id'],
                        'start_time' => $time,
                        'end_time' => null,
                        'date' => Carbon::now()->toDateString()
                    ]);
                } else {
                    //  Update the clock end time
                    $clocks->last()->update(['end_time' => $time]);
                }
            }
        }

        return redirect()->back();
    }
}
