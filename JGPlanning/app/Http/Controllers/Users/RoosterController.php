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

//  Functie om het rooster te laten zien
    public function index(CalendarService $calendarService, $week)
    {
        $current_week = Carbon::now()->setISODate(date('Y'), $week);
        $start_of_week = $current_week->startOfWeek()->format('d-M');
        $end_of_week = $current_week->endOfWeek()->format('d-M');

        $weekstring = $start_of_week . " - ". $end_of_week;

        $week_number = $week;
        $user = Auth::id();
        $weekDays     = Availability::WEEK_DAYS;
        $availability = Rooster::where('user_id', $user)->first();

        $calendarData = $calendarService->generateCalendarData($weekDays, $user);

        $user_info = User::find($user);

        return view('users.rooster.index', compact(
            'user',
            'weekDays',
            'availability',
            'calendarData',
            'user_info',
            'weekstring'
        ));
    }

//  Functie om een dag toe te voegen
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

//  Functie om een dag te bewerken
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

        if (isset($validated['from_home']))
        {
            $from_home = 1;
        }
        else
        {
            $from_home = 0;
        }


        Rooster::all()
            ->where('user_id', $validated['user_id'])
            ->where('weekdays', $validated['weekday'])
            ->first()
            ->update([
                'start_time' => $start_date,
                'end_time' => $end_date,
                'from_home' => $from_home,
                'comment' => "",
            ]);

        return back();
    }

//  Functie om een dag uit de rooster te deleten
    public function delete_rooster($user, $weekday)
    {
        $rooster = Rooster::where('user_id', $user)->where('weekdays', $weekday)->first();

        if (is_null($rooster))
        {
            return back()->with('error', "Couldn't find data of this day");
        }

        $rooster->delete();
        return back();
    }



//  Functie om een gebruikers daggen die disabled zijn te sturen naar de database
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
