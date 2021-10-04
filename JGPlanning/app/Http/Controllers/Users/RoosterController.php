<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Services\CalendarService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RoosterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(CalendarService $calendarService)
    {
        $weekDays     = Availability::WEEK_DAYS;
        $calendarData = $calendarService->generateCalendarData($weekDays);

        return view('users.rooster.index', compact(
            'weekDays',
            'calendarData'
        ));
    }

    public function add_availability()
    {
        for ($i = 1; $i < 6; $i++) {
            $newA = new Availability();

            $newA->user_id = Auth::user()->id;
            $newA->start = "09:00:00";
            $newA->end = "17:00:00";
            $newA->from_home = false;
            $newA->comment = "";
            $newA->date = date('Y-m-d');
            $newA->weekdays = $i;

            $newA->save();
        }
        return back();
    }

    public function edit_availability(Request $request)
    {
        $validated = $request->validate([
            'start_time' => [''],
            'end_time' => [''],
            'weekdays' => ['required',
                Rule::unique('availability')->where(function ($query) {
                    return $query->where('user_id ', Auth::user()->id);
                })
            ],
        ]);


        $Availability = Availability::where('user_id', Auth::user()->id)->where('weekdays', $validated['weekday'])->first();

        if (is_null($Availability))
        {
            return back()->with('error', "We couldn't find Availability on this day.");
        }

        $Availability->start = $validated['start_time'];
        $Availability->end = $validated['end_time'];

        $Availability->save();

        return($Availability->start);
    }

}
