<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Clock;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $clock = Clock::all()
            ->where('user_id', $user['id'])
            ->where('time', '>=', date('Y-m-d').' 00:00:00')
            ->last();
        return view('dashboard.index')
            ->with(['start' => $clock['start'] ?? False]);
    }

    /**
     * @return RedirectResponse
     */
    public function clock(): RedirectResponse
    {
        $user = Auth::user();
        $clocks = Clock::all()
            ->where('user_id', $user['id'])
            ->where('time', '>=', date('Y-m-d').' 00:00:00');
        $message = null;
        if($this->isWorkHours()) {
            if($clocks->count() === 0){
                Clock::create([
                    'time' => Carbon::now()
                        ->addHours(2)
                        ->toDateTimeString(),
                    'start' => True,
                    'comment' => 'Start of Day',
                    'user_id' => $user['id'],
                ]);
            } else {
                $clock = $clocks->last();
                Clock::create([
                    'time' => Carbon::now()
                        ->addHours(2)
                        ->toDateTimeString(),
                    'start' => !$clock['start'],
                    'comment' => 'Start of Day',
                    'user_id' => $user['id'],
                ]);
            }
        } else {
            $message = 'Error';
        }

        return redirect()->back()->with(['error' => $message]);
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
}
