<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Services\CalendarService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use function PHPUnit\Framework\isNull;

class RoosterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(CalendarService $calendarService)
    {
        $user = Auth::user()->id;
        $weekDays     = Availability::WEEK_DAYS;
        $availability = Availability::where('user_id', $user)->get();
        $calendarData = $calendarService->generateCalendarData($weekDays, $user);

        return view('users.rooster.index', compact(
            'weekDays',
            'calendarData',
            'availability',
            'user'
        ));
    }

    public function add_availability(Request $request)
    {
        $validated = $request->validate([
            'start_time' => ['required'],
            'end_time' => ['required'],
            'weekday' => ['required'],
            'user_id' => ['required'],
            'from_home' => [],
            'comment' => [],
        ]);

        $start_time = strtotime($validated['start_time']);
        $start_round = 30*60;
        $start_rounded = round($start_time / $start_round) * $start_round;
        $start_date = date("H:i:s", $start_rounded);


        $end_time = strtotime($validated['end_time']);
        $end_round = 30*60;
        $end_rounded = round($end_time / $end_round) * $end_round;
        $end_date = date("H:i:s", $end_rounded);

        $check_availability = Availability::where('weekdays', $validated['weekday'])->where('user_id', $validated['user_id'])->first();

        if (!$check_availability == null)
        {
            if ($check_availability->start < $validated['end_time'])
            {
                return back()->with('error', 'De uren die je hebt ingevuld overlappen uren die ja al hebt ingeplanned');
            }
        }

        if ($start_date > $end_date)
        {
            return back()->with('error', 'De ingevulde begin tijd is later dan de eind tijd');
        }

        if ($validated['from_home'])
        {
            $from_home = 1;
        }
        else
        {
            $from_home = 0;
        }

        Availability::create([
            'user_id' => $validated['user_id'],
            'start' => $start_date,
            'end' => $end_date,
            'from_home' => $from_home,
            'comment' => $validated['comment'],
            'date' => Carbon::now()->format('Y-m-d'),
            'weekdays' => $validated['weekday'],
        ]);

        return back();
    }

    public function edit_availability(Request $request)
    {
        $validated = $request->validate([
            'start_time' => ['required'],
            'end_time' => ['required'],
            'weekday' => ['required'],
            'user_id' => ['required'],
            'from_home' => [],
            'comment' => [],
        ]);
        $start_time = strtotime($validated['start_time']);
        $start_round = 30*60;
        $start_rounded = round($start_time / $start_round) * $start_round;
        $start_date = date("H:i:s", $start_rounded);


        $end_time = strtotime($validated['end_time']);
        $end_round = 30*60;
        $end_rounded = round($end_time / $end_round) * $end_round;
        $end_date = date("H:i:s", $end_rounded);

        if ($start_date > $end_date)
        {
            return back()->with('error', 'De ingevulde begin tijd is later dan de eind tijd');
        }

        if ($validated['from_home'])
        {
            $from_home = 1;
        }
        else
        {
            $from_home = 0;
        }

        $availability = Availability::where('user_id', $validated['user_id'])->where('weekdays', $validated['weekday'])->first();

        $availability->start = $start_date;
        $availability->end = $end_date;
        $availability->from_home = $from_home;
        $availability->comment = $validated['comment'];

        $availability->update();

        return back();
    }

    public function delete_availability($user, $weekday)
    {
        $availability = Availability::where('user_id', $user)->where('weekdays', $weekday)->first();
        if (is_null($availability))
        {
            return back()->with('error', "Couldn't find data of this day");
        }

        $availability->delete();
        return back();
    }
}
