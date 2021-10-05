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
            'start_time' => ['required'],
            'end_time' => ['required'],
            'weekdays' => ['required'],
        ]);


        $start_time = strtotime($validated['start_time']);
        $start_round = 30*60;
        $start_rounded = round($start_time / $start_round) * $start_round;
        $start_date = date("H:i:s", $start_rounded);


        $end_time = strtotime($validated['end_time']);
        $end_round = 30*60;
        $end_rounded = round($end_time / $end_round) * $end_round;
        $end_date = date("H:i:s", $end_rounded);


        $Availability = Availability::where('user_id', Auth::user()->id)->where('weekdays', $validated['weekdays'])->first();

        if (is_null($Availability))
        {
            return back()->with('error', "We couldn't find Availability on this day.");
        }

        $Availability->start = $start_date;
        $Availability->end = $end_date;

        $Availability->save();

        return back();
    }
}
