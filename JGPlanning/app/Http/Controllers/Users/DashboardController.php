<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Clock;
use App\Services\CheckIfIsInWeek;
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
    public function index(CheckIfIsInWeek $check)
    {
        $user = Auth::user();
        $clock = Clock::all()->where('user_id', $user['id'])->where('date', Carbon::now()->toDateString())->last();

        if($clock == null) {
            return view('dashboard.index')->with(['start' => False]);
        } else if($clock['end_time'] === null) {
            return view('dashboard.index')->with(['start' => True]);
        } else {
            return view('dashboard.index')->with(['start' => False]);
        }
    }

    /**
     * @return RedirectResponse
     */
    public function clock(): RedirectResponse
    {
        $user = Auth::user();
        $clocks = Clock::all()->where('user_id', $user['id'])->where('date', Carbon::now()->toDateString());

        if($clocks->count() == 0) {
            Clock::create([
                'comment' => 'Test Comment 1',
                'user_id' => $user['id'],
                'start_time' => Carbon::now()->addHours(2)->toTimeString(),
                'end_time' => null,
                'date' => Carbon::now()->toDateString()
            ]);
        } else {
            if($clocks->last()['end_time'] != null) {
                Clock::create([
                    'comment' => 'Test Comment 2',
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
}
