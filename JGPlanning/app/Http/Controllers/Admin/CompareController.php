<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CollectionPagination;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        //  Validate the form data when $request is not empty
        $validated = $request->validate([
            'month' => [Rule::requiredIf($request->all() != [])],
            'weeks' => [Rule::requiredIf($request->all() != [])],
            'user' => [Rule::requiredIf($request->all() != [])],
            'date-format' => [Rule::requiredIf($request->all() != [])],
        ]);

        //  Get current week and month
        $month = Carbon::now()->year.'-'.Carbon::now()->month;
        $weeks = Carbon::now()->year.'-W'.Carbon::now()->week;

        //  Set the input type
        $input_field = $validated['date-format'] ?? 'month';

        //  Flash all the $validated data back to the session
        foreach($validated as $index => $value) {
            $request->session()->flash($index, $value);
        }

        //  Get all the users or one user when $validated['user'] is not 0
        $user = $validated['user'] ?? 0;
        $users = User::all()->when(($validated['user'] ?? 0) != 0, function ($query, $user) {
            return $query->where('id', $user);
        });

        //  Paginate the users
        $users = (new CollectionPagination)->paginate($users, 10, request('page'), ['path' => 'vergelijken']);

        //  Return data
        return view('admin.compare.index')->with([
            'users' => $users,
            'all_users' => User::all(),
            'month' => $month,
            'weeks' => $weeks,
            'input_field' => $input_field,
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
                if($user->plannedWorkADayInSeconds($parsed->format('Y'), $parsed->weekOfYear, $parsed->format('d')) > 0 or $user->workedInADayInSeconds($parsed->format('Y'), $parsed->format('m'), $parsed->format('d')) > 0) {
                    $days->push($parsed);
                }
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
