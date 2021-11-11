<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
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
            $weeks = $validated['weeks'];

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

            if($clocks->count() > 0) {
                for($i=1; $i<$last_day_of_month; $i++) {
                    $clocks_of_day = $clocks->where('date', Carbon::parse($year_month.$i)->format('Y-m-d'));

                    if($clocks_of_day->count() > 0) {
                        $first = $clocks_of_day->first();
                        $last = $clocks_of_day->last();

                        $time = 0;
                        foreach($clocks_of_day as $clock) {
                            $time = $time + Carbon::parse($clock['end_time'])->diffInSeconds(Carbon::parse($clock['start_time']));
                        }

                        $entries->push([
                            'date' => Carbon::parse($year_month.$i)->format('Y-m-d'),
                            'day' => Carbon::parse($year_month.$i)->dayOfWeek,
                            'start_time' => Carbon::parse($first['start_time'])->format('H:i'),
                            'end_time' => Carbon::parse($last['end_time'])->format('H:i'),
                            'time' => $time,
                        ]);
                    }
                }
            }
        } elseif ($input_field == 'weeks') {
            $week_number = str_replace('W', '',explode('-', $weeks)[1]);
            $year = explode('-', $weeks)[0];

            $start = Carbon::now()->setISODate($year, $week_number)->format('Y-m-d');
            $end = Carbon::now()->setISODate($year, $week_number, 7)->format('Y-m-d');

            $clocks = $clocks->where('date', '>=', $start)->where('date', '<=', $end);

            if($clocks->count() > 0) {
                for ($i=1; $i < 6; $i++) {
                    $clocks_of_day = $clocks->where('date', Carbon::now()->setISODate($year, $week_number, $i)->format('Y-m-d'));

                    if($clocks_of_day->count() > 0) {
                        $first = $clocks_of_day->first();
                        $last = $clocks_of_day->last();

                        $time = 0;
                        foreach($clocks_of_day as $clock) {
                            $time = $time + Carbon::parse($clock['end_time'])->diffInSeconds(Carbon::parse($clock['start_time']));
                        }

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
            $clocks = $clocks->where('date', $day);

            if($clocks->count() > 0) {
                $first = $clocks->first();
                $last = $clocks->last();

                $time = 0;
                foreach($clocks as $clock) {
                    $time = $time + Carbon::parse($clock['end_time'])->diffInSeconds(Carbon::parse($clock['start_time']));
                }

                $entries->push([
                    'date' => $day,
                    'day' => Carbon::now()->parse($day)->dayOfWeek,
                    'start_time' => Carbon::parse($first['start_time'])->format('H:i'),
                    'end_time' => Carbon::parse($last['end_time'])->format('H:i'),
                    'time' => $time,
                ]);
            }

        }
//        dd(request()->path());
        return view('users.clock.index')->with([
            'user' => $user,
            'month' => $month,
            'weeks' => $weeks,
            'day' => $day,
            'input' => $input_field,
            'entries' => (new CollectionPagination)->paginate($entries, 10, request('page'), ['path' => 'clock'])
        ]);
    }
}
