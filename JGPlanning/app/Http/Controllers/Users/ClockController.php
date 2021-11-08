<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $month = Carbon::now()->year.'-'.Carbon::now()->month;
        $weeks = Carbon::now()->year.'-W'.Carbon::now()->week;
        $day = Carbon::now()->format('Y-m-d');

        $user = Auth::user();

        $input_field = 'month';

        if($request->all() != []) {
            $validated = $request->validate([
                'month' => ['required'],
                'weeks' => ['required'],
                'day' => ['required'],
                'date-format' => ['required'],
            ]);

            $month = $validated['month'];

            $request->session()->flash('month', $validated['month']);
            $request->session()->flash('weeks', $validated['weeks']);
            $request->session()->flash('day', $validated['day']);
            $request->session()->flash('date-format', $validated['date-format']);

            $input_field = $validated['date-format'];
        }

        $clocks = $user->clocks()->get();
        $entries = collect();

        if($input_field == 'month') {
            $year_month = explode('-', $month)[0].'-'.explode('-', $month)[1].'-';
            $start = explode('-', $month)[0].'-'.explode('-', $month)[1].'-01';
            $last_day_of_month = Carbon::parse($start)->daysInMonth;
            $end = explode('-', $month)[0].'-'.explode('-', $month)[1].'-'.$last_day_of_month;

            $clocks = $clocks->where('date', '>=', $start)->where('date', '<=', $end);

            for ($i=1; $i < $last_day_of_month; $i++) {
                $clock = $clocks->where('date', Carbon::parse($year_month.$i)->format('Y-m-d'));

                if($clock->count() != 0) {
                    $first = $clock[0]['start_time'];
                    $last = $clock[array_key_last($clock->toArray())]['end_time'];

                    $entries->push([
                        'date' => Carbon::parse($year_month.$i)->format('Y-m-d'),
                        'start' => $start,
                        'end' => $end
                    ]);
                }
            }
        } elseif ($input_field == 'week') {

        } else {

        }

        return view('users.clock.index')->with([
            'user' => $user,
            'month' => $month,
            'weeks' => $weeks,
            'day' => $day,
            'input' => $input_field,
            'entries' => $entries
        ]);
    }
}
