<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Clock;
use App\Models\User;
use App\Services\CheckIfIsInWeek;
use App\Services\GetWeekdayDate;
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
        $this->middleware('auth');
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $user = Auth::user();
        return view('dashboard.index')->with(['start' => $user->isClockedIn()]);
    }

    /**
     * @return RedirectResponse
     */
    public function clock(Request $request): RedirectResponse
    {
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
                return redirect()->back()->with(['error'=> 'Er kan pas vanaf 08:30 ingeklokt worden']);
            } elseif ($end_time->isPast()) {
                return redirect()->back()->with(['error' => 'Werktijden zijn voorbij, er kan niet meer ingeklokt worden']);
            }
        }


        if($clocks->count() == 0) {
            Clock::create([
                'comment' => $validated['comment'],
                'user_id' => $user['id'],
                'start_time' => Carbon::now()->addHours(2)->toTimeString(),
                'end_time' => null,
                'date' => Carbon::now()->toDateString()
            ]);
        } else {
            if($clocks->last()['end_time'] != null) {
                Clock::create([
                    'comment' => $validated['comment'],
                    'user_id' => $user['id'],
                    'start_time' => Carbon::now()->addHours(2)->toTimeString(),
                    'end_time' => null,
                    'date' => Carbon::now()->toDateString()
                ]);
            } else {
                $clocks->last()->update(['end_time' => Carbon::now()->addHours(2)->toTimeString()]);
            }
        }

        return redirect()->back();
    }

    /**
     * Check if the current time is within acceptable working hours
     * @return bool
     */
    public function isWorkHours(): bool
    {
        $current_time = Carbon::now()->addHours(2);
        $start_time = Carbon::createFromTime(9, 0,0);
        $end_time = Carbon::createFromTime(21, 0,0);

        if($current_time->gt($start_time) and $current_time->lt($end_time)) {
            return True;
        }
        return False;
    }

    public function profile(){
        $user = Auth::user();
        return view('dashboard.profile')->with('user', $user);
    }
}
