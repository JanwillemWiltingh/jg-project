<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Rooster;
use App\Models\User;
use App\Services\CalendarService;
use App\Services\CheckIfIsInWeek;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class RoosterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(CalendarService $calendarService)
    {
        $isRooster = false;
        $user = Auth::user()->id;
        $weekDays     = Availability::WEEK_DAYS;
        $availability = Availability::where('user_id', $user)->get();
        $calendarData = $calendarService->generateCalendarData($weekDays, $user, $isRooster);

        return view('users.rooster.beschikbaarheid.index', compact(
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
            'is_rooster' => ['required'],
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

        if ($validated['is_rooster'])
        {
            $check_availability = Rooster::where('weekdays', $validated['weekday'])->where('user_id', $validated['user_id'])->first();
        }
        else
        {
            $check_availability = Availability::where('weekdays', $validated['weekday'])->where('user_id', $validated['user_id'])->first();
        }

        if (!$check_availability == null)
        {
            if ($check_availability->start-time < $validated['end_time'])
            {
                return back()->with('error', 'De uren die je hebt ingevuld overlappen uren die ja al hebt ingeplanned');
            }
        }
        if ($start_date > $end_date)
        {
            return back()->with('error', 'De ingevulde begin tijd is later dan de eind tijd');
        }

        if ($request->input('from_home'))
        {
            $from_home = 1;
        }
        else
        {
            $from_home = 0;
        }


        Rooster::create([
            'user_id' => $validated['user_id'],
            'start_time' => $start_date,
            'end_time' => $end_date,
            'from_home' => $from_home,
            'comment' => $validated['comment'],
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
            'is_rooster' => ['required'],
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

        if ($validated['is_rooster'])
        {
            $availability = Rooster::where('user_id', $validated['user_id'])->where('weekdays', $validated['weekday'])->first();
        }
        else
        {
            $availability = Availability::where('user_id', $validated['user_id'])->where('weekdays', $validated['weekday'])->first();
        }

        $availability->start_time = $start_date;
        $availability->end_time = $end_date;
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

    public function delete_rooster($user, $weekday)
    {
        $availability = Rooster::where('user_id', $user)->where('weekdays', $weekday)->first();

        if (is_null($availability))
        {
            return back()->with('error', "Couldn't find data of this day");
        }

        $availability->delete();
        return back();
    }


    public function show_rooster(CalendarService $calendarService)
    {
        $isRooster = true;
        $user = Auth::user()->id;
        $weekDays     = Availability::WEEK_DAYS;
        $availability = Rooster::where('user_id', $user)->get();
        $calendarData = $calendarService->generateCalendarData($weekDays, $user, $isRooster);

        $user_info = User::find($user);

        return view('users.rooster.index', compact(
            'user',
            'weekDays',
            'availability',
            'calendarData',
            'user_info'
        ));
    }

    public function push_days($user, Request $request)
    {
        $validated = $request->validate([
            'data' => ['required', 'array']
        ]);



        for ($i = 1; $i < 6; $i++)
        {
            if ($validated['data'][$i - 1])
            {
                if(Rooster::all()->where('user_id', $user)->where('weekdays', $i)->first())
                {
                    $updaterooster = Rooster::where('weekdays', $i)->where('user_id', $user)->update([
                        'disabled' => true
                    ]);
                }
            }
            else
            {
                $updaterooster = Rooster::where('weekdays', $i)->where('user_id', $user)->update([
                    'disabled' => false
                ]);
            }
        }

        $update = User::where('id', $user)->update([
            'unavailable_days' => $validated['data']
        ]);

        return redirect()->back();
    }
}
