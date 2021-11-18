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

        return view('dashboard.index')->with(['start' => $user->isClockedIn(), 'user' => $user, 'now' => $now, 'allowed' => Clock::isIPCorrect($request)]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function clock(Request $request): RedirectResponse
    {
        if(Clock::isIPCorrect($request)) {
            $validated = $request->validate([
                'comment' => ['nullable', 'string'],
            ]);

            $user = Auth::user();
            $clocks = Clock::all()->where('user_id', $user['id'])->where('date', Carbon::now()->toDateString());
            $start_time = Carbon::parse('08:30:00');
            $end_time = Carbon::parse('17:30:00');

//        When someone clocks in before or after working hours give an error message and don't clock them in
            if(!$user->isClockedIn()) {
                if($start_time->isFuture()) {
                    return redirect()->back()->with(['message'=> ['message' => 'Er kan pas vanaf 08:30 ingeklokt worden', 'type' => 'danger']]);
                } elseif ($end_time->isPast()) {
                    return redirect()->back()->with(['message' => ['message' => 'Werktijden zijn voorbij, er kan niet meer ingeklokt worden', 'type' => 'danger']]);
                }
            }

            $now = Carbon::now()->addHours(Clock::ADD_HOURS);
            $hours = $now->format('H');
            $minutes = $now->format('i');
            $rounded_minutes = round($minutes / 15) * 15;

            if(Carbon::parse('09:00')->isFuture() or Carbon::parse('17:00')->isPast()) {
                if($rounded_minutes == 60) {
                    $time = (intval($hours) + 1).':00';
                } else {
                    $time = Carbon::parse($hours.':'.$rounded_minutes)->format('H:i');
                }

            } else {
                $time = $now->format('H:i');
            }


            if($clocks->count() == 0) {
                Clock::create([
                    'comment' => $validated['comment'],
                    'user_id' => $user['id'],
                    'start_time' => $time,
                    'end_time' => null,
                    'date' => Carbon::now()->toDateString()
                ]);
            } else {
                if($clocks->last()['end_time'] != null) {
                    Clock::create([
                        'comment' => $validated['comment'],
                        'user_id' => $user['id'],
                        'start_time' => $time,
                        'end_time' => null,
                        'date' => Carbon::now()->toDateString()
                    ]);
                } else {
                    $clocks->last()->update(['end_time' => $time]);
                }
            }
        }

        return redirect()->back();
    }
}
