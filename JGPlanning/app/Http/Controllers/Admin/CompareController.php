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
            'day' => [Rule::requiredIf($request->all() != [])],
            'user' => [Rule::requiredIf($request->all() != [])],
            'date-format' => [Rule::requiredIf($request->all() != [])],
        ]);

        //  Get current week and month
        $month = Carbon::now()->year.'-'.Carbon::now()->month;
        $weeks = Carbon::now()->year.'-W'.Carbon::now()->week;
        $day = Carbon::now()->format('Y-m-d');

        //  Set the input type
        $input_field = $validated['date-format'] ?? 'month';

        //  Flash all the $validated data back to the session
        foreach($validated as $index => $value) {
            $request->session()->flash($index, $value);
        }

        //  Get all the users or one user when $validated['user'] is not 0
        $user = $validated['user'] ?? 0;
        $users = User::all()->when($user != 0, function ($query) use ($validated) {
            return $query->where('id', $validated['user']);
        });

        //  Paginate the users
        $users = (new CollectionPagination)->paginate($users, 10, request('page'), ['path' => 'vergelijken']);

        //  Return data
        return view('admin.compare.index')->with([
            'users' => $users,
            'all_users' => User::all(),
            'user' => $user,
            'month' => $month,
            'weeks' => $weeks,
            'day' => $day,
            'input_field' => $input_field,
        ]);
    }

    public function show(User $user, $type, $time)
    {
        //  Make two empty collections
        $collection = collect();
        $days = collect();

        if($type == 'day') { // TODO: Make these if statements smaller with a function
            //  if type is day parse it and push it to the collection
            $days->push(Carbon::parse($time));
        }elseif ($type == 'weeks') {
            //  if type is weeks get the week number and make a new date out of it
            $week_number = str_replace('W', '',explode('-', $time)[1]);
            $new_date = new Carbon();
            $first_day_of_week = $new_date->setISODate(explode('-', $time)[0], $week_number);

            //  Loop through the days of the week and add them to the collection
            for ($i=0; $i<6; $i++) {
                if($i==0) {
                    $collection->push($first_day_of_week->format('Y-m-d'));
                } else {
                    $collection->push($first_day_of_week->addDay()->format('Y-m-d'));
                }
            }

            //  Parse every date in the collection
            foreach($collection as $day) {
                $parsed = Carbon::parse($day);
                $days->push($parsed);
            }
        } else {
            $month = explode('-', $time)[1];
            $year = explode('-', $time)[0];

            $new_date = new Carbon($year.'-'.$month.'-01');
            $days_of_month = $new_date->daysInMonth;

            //  Loop through the month and add all days to the collection
            for($i=0; $i < $days_of_month; $i++) {
                if($i==0) {
                    $collection->push($new_date->format('Y-m-d'));
                } else {
                    $collection->push($new_date->addDay()->format('Y-m-d'));
                }
            }

            //  Loop through all the days in the collection and parse them
            foreach($collection as $day) {
                $parsed = Carbon::parse($day);
                if($user->plannedWorkADayInSeconds($parsed->format('Y'), $parsed->weekOfYear, $parsed->format('d')) > 0 or $user->workedInADayInSeconds($parsed->format('Y'), $parsed->format('m'), $parsed->format('d')) > 0) {
                    $days->push($parsed);
                }
            }
        }

        //  Paginate the days collection
        $days = (new CollectionPagination)->paginate($days, 10, request('page'), ['path' => '']);

        return view('admin.compare.show')->with([
            'user' => $user,
            'type' => $type,
            'time' => $time,
            'days' => $days
        ]);
    }
}
