<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Clock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CollectionPagination;

class ClockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, Clock $clock)
    {
        //  Take the current month, week and day
        $month = Carbon::now()->year.'-'.Carbon::now()->month;
        $weeks = Carbon::now()->year.'-W'.Carbon::now()->week;
        $day = Carbon::now()->format('Y-m-d');

        $user = Auth::user();
        $input_field = 'month';

        $clocks = $user->clocks()->get();

        if($request->all() != []) {
            //  If the request is not empty validate it
            $validated = $request->validate([
                'maand' => ['required'],
                'weken' => ['required'],
                'dag' => ['required'],
                'datum-formaat' => ['required'],
            ]);

            //  Put the time in variables
            $month = $validated['maand'];
            $weeks = $validated['weken'];
            $day = $validated['dag'];

            //  Send the validated data back to the session
            $request->session()->flash('month', $validated['maand']);
            $request->session()->flash('weeks', $validated['weken']);
            $request->session()->flash('day', $validated['dag']);
            $request->session()->flash('date-format', $validated['datum-formaat']);

            $input_field = $validated['datum-formaat'];
        }

        $days = collect();
        if($input_field == 'month') {
            $days_in_month = Carbon::parse($month.'-1')->daysInMonth;

            for($i=1; $i<($days_in_month+1); $i++) {
                if($clocks->where('date', $month.'-'.sprintf('%02d', $i))->count() != 0) {
                    $days->push(Carbon::parse($month.'-'.sprintf('%02d', $i)));
                }
            }
        } elseif($input_field == 'weeks') {
            $week_number = explode('-W', $weeks)[1];
            $year = explode('-W', $weeks)[0];

            for($i=0; $i<7; $i++) {
                $day = Carbon::now()->setISODate($year, $week_number, $i);
                if($clocks->where('date', $day->format('Y-m-d'))->count() != 0) {
                    $days->push($day);
                }
            }
        } else {
            if($clocks->where('date', $day)->count() != 0) {
                $days->push(Carbon::parse($day));
            }
        }



        $entries = collect();

        if($input_field == 'month') { // TODO: If statements shorter with functions
            //  Get the month from the month input
            $year_month = explode('-', $month)[0].'-'.explode('-', $month)[1].'-';

            //  Get the start and end day of the month
            $start = explode('-', $month)[0].'-'.explode('-', $month)[1].'-01';
            $last_day_of_month = Carbon::parse($start)->daysInMonth;
            $end = explode('-', $month)[0].'-'.explode('-', $month)[1].'-'.$last_day_of_month;

            //  Get all the clocks from the given month with the start and end days
            $clocks = $clocks->where('date', '>=', $start)->where('date', '<=', $end);

            if($clocks->count() > 0) {
                //  If there are clocks loop through them for every day of the month
                for($i=1; $i<$last_day_of_month; $i++) {
                    //  Get all the clocks for the given day
                    $clocks_of_day = $clocks->where('date', Carbon::parse($year_month.$i)->format('Y-m-d'));

                    if($clocks_of_day->count() > 0) {
                        //  If there are clocks get the first and last clock of that day
                        $first = $clocks_of_day->first();
                        $last = $clocks_of_day->last();

                        //  Loop through the clocks and add the calculated time to the $time variable
                        $time = 0;
                        foreach($clocks_of_day as $clock) {
                            if($clock['end_time'] != null) {
                                $time = $time + Carbon::parse($clock['end_time'])->diffInSeconds(Carbon::parse($clock['start_time']));
                            } else {
                                $time = $time + Carbon::parse(Carbon::now()->addHours(Clock::ADD_HOURS)->format('Y-m-d'))->diffInSeconds(Carbon::parse($clock['start_time']));
                            }

                        }

                        $end_time = null;
                        if($last['end_time'] != null) {
                            $end_time = Carbon::parse($last['end_time'])->format('H:i');
                        }
                        //  For every day add an entry to the entries variable
                        $entries->push([
                            'date' => Carbon::parse($year_month.$i)->format('Y-m-d'),
                            'day' => Carbon::parse($year_month.$i)->dayOfWeek,
                            'start_time' => Carbon::parse($first['start_time'])->format('H:i'),
                            'end_time' => $end_time,
                            'time' => $time,
                        ]);
                    }
                }
            }
        } elseif ($input_field == 'weeks') {
            //  Get the week and year from the week variable
            $week_number = str_replace('W', '',explode('-', $weeks)[1]);
            $year = explode('-', $weeks)[0];

            //  Get the start and end day of the week
            $start = Carbon::now()->setISODate($year, $week_number)->format('Y-m-d');
            $end = Carbon::now()->setISODate($year, $week_number, 7)->format('Y-m-d');

            //  Get all the clocks with in the start and end days
            $clocks = $clocks->where('date', '>=', $start)->where('date', '<=', $end);

            if($clocks->count() > 0) {
                //  If there are clocks look through them for every workday in the week
                for ($i=1; $i < 6; $i++) {
                    //  Get all the clocks from the given day
                    $clocks_of_day = $clocks->where('date', Carbon::now()->setISODate($year, $week_number, $i)->format('Y-m-d'));

                    if($clocks_of_day->count() > 0) {
                        //  If there are clocks get the first and last clock of the day
                        $first = $clocks_of_day->first();
                        $last = $clocks_of_day->last();

                        //  Loop through the clocks and calculate the time
                        $time = 0;
                        foreach($clocks_of_day as $clock) {
                            $time = $time + Carbon::parse($clock['end_time'])->diffInSeconds(Carbon::parse($clock['start_time']));
                        }

                        //  Add every day as an entry to entries
                        $entries->push([
                            'date' => Carbon::now()->setISODate($year, $week_number, $i)->format('Y-m-d'),
                            'day' => Carbon::now()->setISODate($year, $week_number, $i)->dayOfWeek,
                            'start_time' => Carbon::parse($first['start_time'])->format('H:i'),
                            'end_time' => Carbon::parse($last['end_time'])->format('H:i'),
                            'time' => $time,
                        ]);
                    }
                }
            }
        } else {
            //  Get all the clocks from the given day
            $clocks = $clocks->where('date', $day);

            if($clocks->count() > 0) {
                //  If there are clocks get the first and last clock
                $first = $clocks->first();
                $last = $clocks->last();

                //  Loop through the clocks and get the time difference
                $time = 0;
                foreach($clocks as $clock) {
                    $time = $time + Carbon::parse($clock['end_time'])->diffInSeconds(Carbon::parse($clock['start_time']));
                }

                //  Add every day as an entry to entries
                $entries->push([
                    'date' => $day,
                    'day' => Carbon::now()->parse($day)->dayOfWeek,
                    'start_time' => Carbon::parse($first['start_time'])->format('H:i'),
                    'end_time' => Carbon::parse($last['end_time'])->format('H:i'),
                    'time' => $time,
                    'user' => $user,
                ]);
            }

        }

        return view('users.clock.index')->with([
            'clock' => $clock,
            'user' => $user,
            'days' => $days,
            'month' => $month,
            'weeks' => $weeks,
            'day' => $day,
            'input' => $input_field,
            'entries' => (new CollectionPagination)->paginate($entries, 10, request('page'), ['path' => 'clock'])
        ]);
    }
    public function show($id)
    {
        $clock = Clock::find($id);
        return view('users.clock.show')->with(['clock' => $clock]);
    }
}
