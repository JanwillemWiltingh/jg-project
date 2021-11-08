<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $users = User::all();
        $all_users = User::all();
        $month = Carbon::now()->year.'-'.Carbon::now()->month;
        $weeks = Carbon::now()->year.'-W'.Carbon::now()->week;

        $input_field = 'month';
        if($request->all() != []) {
            $validated = $request->validate([
                'month' => ['required'],
                'weeks' => ['required'],
                'user' => ['required'],
                'date-format' => ['required'],
            ]);

            $request->session()->flash('month', $validated['month']);
            $request->session()->flash('weeks', $validated['weeks']);
            $request->session()->flash('user', $validated['user']);
            $request->session()->flash('date-format', $validated['date-format']);

            if($validated['user'] != 0) {
                $users = User::where('id', $validated['user'])->get();
            }

            $input_field = $validated['date-format'];
        }

        return view('admin.compare.index')->with([
            'users' => $users,
            'all_users' => $all_users,
            'month' => $month,
            'weeks' => $weeks,
            'input_field' => $input_field
        ]);
    }

    public function show(User $user, $type, $time)
    {
        $collection = collect();
        $days = collect();

        if($type == 'weeks') {
            $week_number = str_replace('W', '',explode('-', $time)[1]);
            $new_date = new Carbon();
            $first_day_of_week = $new_date->setISODate(explode('-', $time)[0], $week_number);

            for ($i=0; $i<6; $i++) {
                if($i==0) {
                    $collection->push($first_day_of_week->format('Y-m-d'));
                } else {
                    $collection->push($first_day_of_week->addDay(1)->format('Y-m-d'));
                }
            }

            foreach($collection as $day) {
                $parsed = Carbon::parse($day);
                $days->push($parsed);
            }
        } else {
            $month = explode('-', $time)[1];
            $year = explode('-', $time)[0];

            $new_date = new Carbon($year.'-'.$month.'-01');
            $days_of_month = $new_date->daysInMonth;

            for($i=0; $i < $days_of_month; $i++) {
                if($i==0) {
                    $collection->push($new_date->format('Y-m-d'));
                } else {
                    $collection->push($new_date->addDay()->format('Y-m-d'));
                }
            }

            foreach($collection as $day) {
                $parsed = Carbon::parse($day);
                $days->push($parsed);
            }
        }

        return view('admin.compare.show')->with([
            'user' => $user,
            'type' => $type,
            'time' => $time,
            'days' => $days
        ]);
    }
}
