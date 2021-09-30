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
            // error want buiten werk tijden
        }


        return redirect()->back();
    }

    public function isWorkHours(): bool
    {
        $current_time = Carbon::now()
            ->addHours(2)
            ->toTimeString();
        if($current_time->lt('09:30:00') or $current_time->gt('21:00:00')) {
            return True;
        }
        return False;
    }
}
