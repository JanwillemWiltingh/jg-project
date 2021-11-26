<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Services\TimeService;
use App\Models\{User,Rooster,DisabledDays,Availability};
use App\Services\CalendarService;
use App\Services\CheckIfIsInWeek;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use function GuzzleHttp\Promise\all;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class RoosterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

//  Functie om het rooster te laten zien
    public function index(CalendarService $calendarService, $week, $year)
    {
        if ($week > 52)
        {
            $targetYear = $year + 1;
            return redirect('/rooster/1/' . $targetYear);
        }
        else if($week < 1)
        {
            $targetYear = $year - 1;
            return redirect('/rooster/52/' . $targetYear);
        }

        $weekDays     = Availability::WEEK_DAYS;

        // Datums
        $date = Carbon::now();
        $current_week = $date->setISODate($year, $week)->format('Y-m-d');
        $current_week_display = $date->setISODate($year, $week);
        $start_of_week_display = $current_week_display->startOfWeek()->format('d-M');
        $end_of_week_display = $current_week_display->endOfWeek()->format('d-M');


        $weekstring = $start_of_week_display . " - ". $end_of_week_display;

        // Gebruiker Data
        $user = Auth::id();

        // weekday Data
        $availability = Rooster::all()->where('user_id', $user);

        $array1 = [];
        $disabled_array = [];
        $disabled_days = DisabledDays::all()
            ->where('user_id', $user)
            ->where('start_week', '<=', $week)
            ->where('end_week', '>=', $week)
            ->sortBy('weekday');
        $disabled = DisabledDays::all()->where('user_id', $user);

        foreach ($disabled_days as $dis)
        {
            array_push($array1, $dis->weekday);
        }

        foreach ($weekDays as $index => $day)
        {
            if (in_array($index, $array1))
            {
                array_push($disabled_array, 1);
            }
            else
            {
                array_push($disabled_array, null);
            }
        }


        $weekDays     = Availability::WEEK_DAYS;
        $calendarData = $calendarService->generateCalendarData($weekDays, $user, $week, $year);
        $user_info = User::find($user);

        return view('users.rooster.index', compact(
            'user',
            'weekDays',
            'availability',
            'calendarData',
            'user_info',
            'weekstring',
            'disabled_array',
            'disabled_days',
            'disabled'
        ));
    }

//  Functie om een dag toe te voegen
    public function add_availability(TimeService $time, Request $request, $start_week)
    {
        $validated = $request->validate([
            'start_time' => ['required'],
            'end_time' => ['required'],
            'weekday' => ['required'],
            'user_id' => ['required'],
            'comment' => [],
            'begin_week' => ['required'],
            'week' => ['required'],
        ]);

        $start_time = $time->roundTime($validated['start_time'], 30);

        $end_time  = $time->roundTime($validated['end_time'], 30);

        $start_year = substr($validated['begin_week'],'0', '-4');

        $end_year  = substr($validated['week'],'0', '-4');

        $start_week = substr($validated['begin_week'], 6);

        $end_week = substr($validated['week'], 6);

//        Info voor if statements
        $date = Carbon::now();
        $checkRooster = Rooster::all()
            ->where('weekdays', $validated['weekday'])
            ->where('user_id', $validated['user_id']);

        if ($validated['weekday'] == 1)
        {
            $final_date_start = $date
                ->setISODate($start_year, $start_week)
                ->format('Y-m-d');
            $final_date_end = $date
                ->setISODate($end_year, $end_week)
                ->format('Y-m-d');
        }
        else
        {
            $final_date_start = $date
                ->setISODate($start_year, $start_week)
                ->addDays($validated['weekday'])
                ->format('Y-m-d');
            $final_date_end = $date
                ->setISODate($end_year, $end_week)
                ->addDays($validated['weekday'])
                ->format('Y-m-d');
        }

//        Checked of de week die is ingevuld niet eerder is dan de huidige week.
        foreach ($checkRooster as $cr)
        {
            if ($cr->weekday == 1)
            {
                $final_db_date_start = $date
                    ->setISODate($cr->start_year, $cr->start_week)
                    ->format('Y-m-d');
                $final_db_date_end = $date
                    ->setISODate($cr->end_year, $cr->end_week)
                    ->format('Y-m-d');
            }
            else
            {
                $final_db_date_start = $date
                    ->setISODate($cr->start_year, $cr->start_week)
                    ->addDays($cr->weekday)
                    ->format('Y-m-d');
                $final_db_date_end = $date
                    ->setISODate($cr->end_year, $cr->end_week)
                    ->addDays($cr->weekday)
                    ->format('Y-m-d');
            }
            if (($final_date_start >= $final_db_date_start) && ($final_date_start <= $final_db_date_end))
            {
                return back()->with(['message' => ['message' => 'de weken die je hebt ingevuld overlappen met weken die al ingevuld zijn.', 'type' => 'danger']]);
            }
            else if (($final_date_end >= $final_db_date_start) && ($final_date_end <= $final_db_date_end))
            {
                return back()->with(['message' => ['message' => 'de weken die je hebt ingevuld overlappen met weken die al ingevuld zijn.', 'type' => 'danger']]);
            }
        }

        if ($final_date_start > $final_date_end)
        {
            return back()->with(['message' => ['message' => 'De week die u heeft ingevuld is eerder dan de begin week', 'type' => 'danger']]);
        }

//        Kijkt of de begin tijd niet later is dan de eind tijd die is ingevuld
        if ($start_time > $end_time)
        {
            return back()->with(['message' => ['message' => 'De ingevulde begin tijd is later dan de eind tijd', 'type' => 'danger']]);
        }

//        handled de from_home togglebox
        if ($request->input('from_home'))
        {
            $from_home = 1;
        }
        else
        {
            $from_home = 0;
        }

//        creërt een record in de database voor de ingevulde dag

        Rooster::create([
            'user_id' => $validated['user_id'],
            'start_time' => $start_time,
            'end_time' => $end_time,
            'from_home' => $from_home,
            'comment' => $validated['comment'],
            'weekdays' => $validated['weekday'],
            'start_week' => $start_week,
            'end_week' => $end_week,
            'start_year' => $start_year,
            'end_year' => $end_year,
        ]);

        return back()->with(['message' => ['message' => 'Succesvol dag ingevuld', 'type' => 'success']]);
    }

//  Functie om een dag te bewerken
    public function edit_availability(TimeService $time, Request $request, $week)
    {
        $validated = $request->validate([
            'start_time' => ['required'],
            'end_time' => ['required'],
            'weekday' => ['required'],
            'user_id' => ['required'],
            'from_home' => [],
            'comment' => [],
            'start_week' => ['required'],
            'end_week' => ['required'],
            'rooster_id' => ['required']
        ]);

        $start_time = $time->roundTime($validated['start_time'], 30);

        $end_time  = $time->roundTime($validated['end_time'], 30);

        $start_week = substr($validated['start_week'], 6);
        $end_week = substr($validated['end_week'], 6);

        $start_year = substr($validated['start_week'], '0',-4);
        $end_year = substr($validated['end_week'], '0', -4);


        $date = Carbon::now();
        if ($validated['weekday'] == 1)
        {
            $final_date_start = $date
                ->setISODate($start_year, $start_week)
                ->format('Y-m-d');
            $final_date_end = $date
                ->setISODate($end_year, $end_week)
                ->format('Y-m-d');
        }
        else
        {
            $final_date_start = $date
                ->setISODate($start_year, $start_week)
                ->addDays($validated['weekday'])
                ->format('Y-m-d');
            $final_date_end = $date
                ->setISODate($end_year, $end_week)
                ->addDays($validated['weekday'])
                ->format('Y-m-d');
        }

        $checkRoosterEdit = Rooster::all()
            ->where('user_id', $validated['user_id'])
            ->where('weekdays', $validated['weekday']);

//      Checked of de ingevulde datums niet overlappen met andere datums van de gebruiker.
        foreach ($checkRoosterEdit as $cr)
        {
            if ($cr->weekday == 1)
            {
                $final_db_date_start = $date
                    ->setISODate($cr->start_year, $cr->start_week)
                    ->format('Y-m-d');
                $final_db_date_end = $date
                    ->setISODate($cr->end_year, $cr->end_week)
                    ->format('Y-m-d');
            }
            else
            {
                $final_db_date_start = $date
                    ->setISODate($cr->start_year, $cr->start_week)
                    ->addDays($cr->weekday)
                    ->format('Y-m-d');
                $final_db_date_end = $date
                    ->setISODate($cr->end_year, $cr->end_week)
                    ->addDays($cr->weekday)
                    ->format('Y-m-d');
            }
            if (($final_date_start >= $final_db_date_start) && ($final_date_start <= $final_db_date_end))
            {
                return back()->with(['message' => ['message' => 'de weken die je hebt ingevuld overlappen met weken die al ingevuld zijn.', 'type' => 'danger']]);
            }
            else if (($final_date_end >= $final_db_date_start) && ($final_date_end <= $final_db_date_end))
            {
                return back()->with(['message' => ['message' => 'de weken die je hebt ingevuld overlappen met weken die al ingevuld zijn.', 'type' => 'danger']]);
            }
        }

//      Checked of de begin week die is ingevuld niet eerder is dan de ingevulde eind week.
        if ($final_date_end < $final_date_start)
        {
            return back()->with(['message' => ['message' => 'De begin week die u heeft ingevuld is later dan de ingevulde eind week.', 'type' => 'danger']]);
        }

//      Checked of de begin tijd die is ingevuld niet eerder is dan de ingevulde eind tijd.
        if ($start_time > $end_time)
        {
            return back()->with(['message' => ['message' => 'De ingevulde begin tijd is later dan de eind tijd.', 'type' => 'danger']]);
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
            ->where('id', $validated['rooster_id'])
            ->first()
            ->update([
                'start_time' => $start_time,
                'end_time' => $end_time,
                'from_home' => $from_home,
                'comment' => $validated['comment'],
                'start_week' => $start_week,
                'end_week' => $end_week,
                'start_year' => $start_year,
                'end_year' => $end_year
            ]);

        return back()->with(['message' => ['message' => 'De aangegeven planning is aangepast.', 'type' => 'success']]);
    }

//  Functie om een dag uit de rooster te deleten
    public function delete_rooster($user, $weekday, $week)
    {
        $rooster = Rooster::all()
            ->where('user_id', $user)
            ->where('weekdays', $weekday)
            ->where('start_week', '<=', $week)
            ->where('end_week', '>=', $week)
            ->first();

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

//  functie om uitgezette datums aan te makken.
    public function disable_days(Request $request)
    {
        $validated = $request->validate([
            'weekday' => ['required'],
            'start_week' => ['required'],
            'end_week' => ['required'],
            'user_id' => ['required']
        ]);

        $user = Auth::id();

        $start_year = substr($validated['start_week'],'0', '-4');

        $end_year  = substr($validated['end_week'],'0', '-4');

        $start_week = substr($validated['start_week'], 6);

        $end_week = substr($validated['end_week'], 6);

//      Info voor if statements
        $date = Carbon::now();
        $checkRooster = DisabledDays::all()
            ->where('weekday', $validated['weekday'])
            ->where('user_id', $validated['user_id']);

        if ($validated['weekday'] == 1)
        {
            $final_date_start = $date
                ->setISODate($start_year, $start_week)
                ->format('Y-m-d');
            $final_date_end = $date
                ->setISODate($end_year, $end_week)
                ->format('Y-m-d');
        }
        else
        {
            $final_date_start = $date
                ->setISODate($start_year, $start_week)
                ->addDays($validated['weekday'])
                ->format('Y-m-d');
            $final_date_end = $date
                ->setISODate($end_year, $end_week)
                ->addDays($validated['weekday'])
                ->format('Y-m-d');
        }


//        Checked of de week die is ingevuld niet eerder is dan de huidige week.
        foreach ($checkRooster as $cr)
        {
            if ($cr->weekday == 1)
            {
                $final_db_date_start = $date
                    ->setISODate($cr->start_year, $cr->start_week)
                    ->format('Y-m-d');
                $final_db_date_end = $date
                    ->setISODate($cr->end_year, $cr->end_week)
                    ->format('Y-m-d');
            }
            else
            {
                $final_db_date_start = $date
                    ->setISODate($cr->start_year, $cr->start_week)
                    ->addDays($cr->weekday)
                    ->format('Y-m-d');
                $final_db_date_end = $date
                    ->setISODate($cr->end_year, $cr->end_week)
                    ->addDays($cr->weekday)
                    ->format('Y-m-d');
            }
            if (($final_date_start >= $final_db_date_start) && ($final_date_start <= $final_db_date_end))
            {
                return back()->with(['message' => ['message' => 'de weken die je hebt ingevuld overlappen met weken die al ingevuld zijn.', 'type' => 'danger']]);
            }
            else if (($final_date_end >= $final_db_date_start) && ($final_date_end <= $final_db_date_end))
            {
                return back()->with(['message' => ['message' => 'de weken die je hebt ingevuld overlappen met weken die al ingevuld zijn.', 'type' => 'danger']]);
            }
        }

        if ($final_date_start > $final_date_end)
        {
            return back()->with(['message' => ['message' => 'De ingevulde begin week is later dan de eind week', 'type' => 'danger']]);
        }

        DisabledDays::create([
            'user_id' => $validated['user_id'],
            'weekday' => $validated['weekday'],
            'start_week' => $start_week,
            'end_week' => $end_week,
            'start_year' => $start_year,
            'end_year' => $end_year,
        ]);

        return back()->with(['message' => ['message' => 'De ingevulde weken zijn uitgezet.', 'type' => 'success']]);
    }

    public function delete_disable($weekday, $week)
    {
        DisabledDays::all()
            ->where('id', Auth::id())
            ->where('weekday', $weekday)
            ->where('start_week', '<=', $week)
            ->where('end_week', '>=', $week)
            ->first()
            ->delete();

        return back();
    }

    public function manage_disable_days(Request $request)
    {
        $validate = $request->validate([
            'id' => ['required'],
        ]);

        DisabledDays::all()
            ->where('id', $validate['id'])
            ->first()
            ->delete();
    }
    public function manage_delete_days(Request $request)
    {
        $validate = $request->validate([
            'id' => ['required'],
        ]);

        Rooster::all()
            ->where('id', $validate['id'])
            ->first()
            ->delete();
    }

    public function edit_disable_days(Request $request, $week)
    {
        $validated = $request->validate([
            'id' => ['required'],
            'start_week' => ['required'],
            'end_week' => ['required']
        ]);
        $start_year = substr($validated['start_week'],'0', '-4');

        $end_year  = substr($validated['end_week'],'0', '-4');

        $start_week = substr($validated['start_week'], 6);

        $end_week = substr($validated['end_week'], 6);

//      Info voor if statements
        $date = Carbon::now();
        $checkDisabled = DisabledDays::all()
            ->where('id', $validated['id'])
            ->where('user_id', Auth::id());

        if ($checkDisabled[0]->weekday == 1)
        {
            $final_date_start = $date
                ->setISODate($start_year, $start_week)
                ->format('Y-m-d');
            $final_date_end = $date
                ->setISODate($end_year, $end_week)
                ->format('Y-m-d');
        }
        else
        {
            $final_date_start = $date
                ->setISODate($start_year, $start_week)
                ->addDays($checkDisabled[0]->weekday)
                ->format('Y-m-d');
            $final_date_end = $date
                ->setISODate($end_year, $end_week)
                ->addDays($checkDisabled[0]->weekday)
                ->format('Y-m-d');
        }

//        Checked of de week die is ingevuld niet eerder is dan de huidige week.
        foreach ($checkDisabled as $cr)
        {
            if ($cr->weekday == 1)
            {
                $final_db_date_start = $date
                    ->setISODate($cr->start_year, $cr->start_week)
                    ->format('Y-m-d');
                $final_db_date_end = $date
                    ->setISODate($cr->end_year, $cr->end_week)
                    ->format('Y-m-d');
            }
            else
            {
                $final_db_date_start = $date
                    ->setISODate($cr->start_year, $cr->start_week)
                    ->addDays($cr->weekday)
                    ->format('Y-m-d');
                $final_db_date_end = $date
                    ->setISODate($cr->end_year, $cr->end_week)
                    ->addDays($cr->weekday)
                    ->format('Y-m-d');
            }
            if ($cr->id != $validated['id'])
            {
                if (($final_date_start >= $final_db_date_start) && ($final_date_start <= $final_db_date_end))
                {
                    return back()->with(['message' => ['message' => 'de weken die je hebt ingevuld overlappen met weken die al ingevuld zijn.', 'type' => 'danger']]);
                }
                else if (($final_date_end >= $final_db_date_start) && ($final_date_end <= $final_db_date_end))
                {
                    return back()->with(['message' => ['message' => 'de weken die je hebt ingevuld overlappen met weken die al ingevuld zijn.', 'type' => 'danger']]);
                }
            }
        }
        if ($final_date_start > $final_date_end)
        {
            return back()->with(['message' => ['message' => 'De ingevulde begin week is later dan de eind week', 'type' => 'danger']]);
        }

        $update = DisabledDays::find($validated['id']);
        $update->update([
            'start_week' => $start_week,
            'end_week' => $end_week,
            'by_admin' => false
        ]);

        return back()->with(['message' => ['message' => 'De aangegeven weken zijn aangepast', 'type' => 'success']]);
    }
}
