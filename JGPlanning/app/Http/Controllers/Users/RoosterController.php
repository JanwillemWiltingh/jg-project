<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Services\RosterService;
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
    public function index(RosterService $rosterService,CalendarService $calendarService, $week, $year)
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

        $checkRooster = Rooster::all()->where('user_id', Auth::id());
        if ($checkRooster->count() == 0) {
            for ($i = 1; $i < 6; $i++)
            {
                Rooster::create([
                    'start_time' => '08:30:00',
                    'end_time' => '17:00:00',
                    'comment' => "",
                    'from_home' => 0,
                    'weekdays' => $i,
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => null,
                    'user_id' => Auth::id(),
                    'start_week' => '1',
                    'end_week' => '52',
                    'disabled' => false,
                    'start_year' => date('Y'),
                    'end_year' => date('Y'),
                ]);
            }
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
        $roster = $rosterService->generateRosterData($user);
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
            'disabled',
            'roster'
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
                return back()->with(['message' => ['message' => 'De weken die u heeft ingevuld overlappen met weken die al ingevuld zijn.', 'type' => 'danger']]);
            }
            else if (($final_date_end >= $final_db_date_start) && ($final_date_end <= $final_db_date_end))
            {
                return back()->with(['message' => ['message' => 'De weken die u heeft ingevuld overlappen met weken die al ingevuld zijn.', 'type' => 'danger']]);
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
    public function edit_availability(TimeService $time, Request $request, $week, $year)
    {
        $edit_rooster = Rooster::all()
            ->where('id', $request->input('rooster_id'))
            ->first();
        $date = Carbon::now();
        if ($request->input('start_time'))
        {
            if ($edit_rooster->end_time > $request->input('start_time'))
            {
                $edit_rooster->update(['start_time' => $time->roundTime($request->input('start_time'), 30)]);
            }
            else
            {
                if ($edit_rooster->start_time != $request->input('start_time'). ":00")
                {
                    return back()->with(['message' => ['message' => 'De ingevulde begin tijd is later dan de eind tijd.', 'type' => 'danger']]);
                }
            }
        }

        if ($request->input('end_time'))
        {
            if (!$edit_rooster->start_time > $request->input('end_time'))
            {
                $edit_rooster->update(['end_time' => $time->roundTime($request->input('end_time'), 30)]);
            }
            else
            {
                if ($edit_rooster->end_time != $request->input('end_time'). ":00")
                {
                    return back()->with(['message' => ['message' => 'De ingevulde eind tijd is eerder dan de start tijd.', 'type' => 'danger']]);
                }
            }
        }

        if ($request->input('from_home'))
        {
            $edit_rooster->update(['from_home' => true]);
        }
        else
        {
            $edit_rooster->update(['from_home' => false]);
        }

        if ($request->input('comment'))
        {
            $edit_rooster->update(['comment' => $request->input('comment')]);
        }

        if ($request->input('start_week'))
        {
            $start_year = substr($request->input('start_week'), '0',-4);
            $start_week = substr($request->input('start_week'), 6);
//            dd($start_year, $start_week);
            if ($request->input('weekday') == 1)
            {
                $final_date_start = $date
                    ->setISODate($start_year, $start_week)
                    ->format('Y-m-d');
            }
            else
            {
                $final_date_start = $date
                    ->setISODate($start_year, $start_week)
                    ->addDays($request->input('weekday'))
                    ->format('Y-m-d');
            }

            $checkRoosterEdit = Rooster::all()
                ->where('user_id', $request->input('user_id'))
                ->where('weekdays', $request->input('weekday'));
            if ($edit_rooster->start_week != $start_week || $edit_rooster->start_year != $start_year)
            {
//              Checked of de ingevulde datums niet overlappen met andere datums van de gebruiker.
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
                    if ($cr->id == $request->input('rooster_id'))
                    {
                        if (($final_date_start >= $final_db_date_start) && ($final_date_start <= $final_db_date_end))
                        {
                            $edit_rooster->update(['start_week' => $start_week]);
                            $edit_rooster->update(['start_year' => $start_year]);
                        }
                        else
                        {
                            return back()->with(['message' => ['message' => 'De eind week die u heeft ingevuld overlapt met weken die al ingevuld zijn.', 'type' => 'danger']]);
                        }
                    }
                }
            }

        }

        if ($request->input('end_week'))
        {
            $start_year = substr($request->input('end_week'), '0',-4);
            $start_week = substr($request->input('end_week'), 6);
            if ($request->input('weekday') == 1)
            {
                $final_date_end = $date
                    ->setISODate($start_year, $start_week)
                    ->format('Y-m-d');
            }
            else
            {
                $final_date_end = $date
                    ->setISODate($start_year, $start_week)
                    ->addDays($request->input('weekday'))
                    ->format('Y-m-d');
            }

            $checkRoosterEdit = Rooster::all()
                ->where('user_id', $request->input('user_id'))
                ->where('weekdays', $request->input('weekday'));
            if ($edit_rooster->end_week != $start_week || $edit_rooster->end_year != $start_year) {
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

                    if ($cr->id == $request->input('rooster_id'))
                    {
                        if (($final_date_end >= $final_db_date_start) && ($final_date_end <= $final_db_date_end))
                        {
                            $edit_rooster->update(['end_week' => $start_week]);
                            $edit_rooster->update(['end_year' => $start_year]);
                        }
                        else
                        {
                            return back()->with(['message' => ['message' => 'De eind week die u heeft ingevuld overlapt met weken die al ingevuld zijn.', 'type' => 'danger']]);
                        }
                    }
                }
            }
        }

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

//  Functie om uitgezette datums aan te makken.
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
                return back()->with(['message' => ['message' => 'De weken die u heeft ingevuld overlappen met weken die al ingevuld zijn.', 'type' => 'danger']]);
            }
            else if (($final_date_end >= $final_db_date_start) && ($final_date_end <= $final_db_date_end))
            {
                return back()->with(['message' => ['message' => 'De weken die u heeft hebt ingevuld overlappen met weken die al ingevuld zijn.', 'type' => 'danger']]);
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

//  Functie om uitgezette datums normaal te verwijderen
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

//  Functie om uitgezette datums normaal te verwijderen
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

//  Functie om rooster datums te verwijderen
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

//  Functie om rooster datums te bewerken
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
            ->where('weekday', DisabledDays::all()->where('id', $validated['id'])->first()->weekday)
            ->where('user_id', Auth::id());
        if (DisabledDays::all()->where('id', $validated['id'])->first()->weekday == 1)
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
                ->addDays(DisabledDays::all()->where('id', $validated['id'])->first()->weekday)
                ->format('Y-m-d');
            $final_date_end = $date
                ->setISODate($end_year, $end_week)
                ->addDays(DisabledDays::all()->where('id', $validated['id'])->first()->weekday)
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

//  Functie om rooster datums te bewerken
    public function disable_days_click($week, $year, $day)
    {
        $checkDisabled = DisabledDays::all()
            ->where('user_id', Auth::id())
            ->where('weekday', $day);
        foreach ($checkDisabled as $cr)
        {
            if ($cr->start_week == $week)
            {
                if ($cr->start_week == $cr->end_week)
                {
                    $cr->delete();
                }
                else
                {
                    $cr->update([
                        'start_week' => $week + 1,
                        'start_year' => $year,
                    ]);
                }
                return back()->with(['message' => ['message' => 'Dag opengezet.', 'type' => 'success']]);
            }
            else if ($cr->end_week == $week)
            {
                if ($cr->start_week == $cr->end_week)
                {
                    $cr->delete();
                }
                else
                {
                    $cr->update([
                        'end_week' => $week - 1,
                        'end_year' => $year,
                    ]);
                }
                return back()->with(['message' => ['message' => 'Dag opengezet.', 'type' => 'success']]);
            }
            else if (in_array($week, range($cr->start_week, $cr->end_week)))
            {
                DisabledDays::create([
                    'user_id' => Auth::id(),
                    'start_week' => ($week + 1),
                    'end_week' => $cr->end_week,
                    'weekday' => $cr->weekday,
                    'start_year' => $cr->start_year,
                    'end_year' => $cr->end_year,
                ]);
                $cr->update([
                    'end_week' => ($week - 1),
                ]);
                return back()->with(['message' => ['message' => 'Dag opengezet.', 'type' => 'success']]);
            }
            else if ($cr->start_week - 1 == $week)
            {
                $cr->update([
                    'start_week' => $week,
                    'start_year' => $year,
                ]);
                return back()->with(['message' => ['message' => 'Dag uitgezet.', 'type' => 'success']]);
            }
            else if ($cr->end_week + 1 == $week)
            {
                $cr->update([
                    'end_week' => $week,
                    'end_year' => $year,
                ]);
                return back()->with(['message' => ['message' => 'Dag uitgezet.', 'type' => 'success']]);
            }
            else
            {
                DisabledDays::create([
                    'user_id' => Auth::id(),
                    'start_week' => $week,
                    'end_week' => $week,
                    'weekday' => $day,
                    'start_year' => $year,
                    'end_year' => $year,
                ]);
                return back()->with(['message' => ['message' => 'Dag uitgezet.', 'type' => 'success']]);
            }
        }
        if ($checkDisabled->count() == 0)
        {
            DisabledDays::create([
                'user_id' => Auth::id(),
                'start_week' => $week,
                'end_week' => $week,
                'weekday' => $day,
                'start_year' => $year,
                'end_year' => $year,
            ]);
            return back()->with(['message' => ['message' => 'Dag uitgezet.', 'type' => 'success']]);
        }

//        TODO: een betere plaats voor deze code vinden.
//        foreach ($checkDisabled as $dis1)
//        {
//            foreach ($checkDisabled as $dis2)
//            {
//                if ($dis1->weekday == $dis2->weekday)
//                {
//                    if ($dis1->end_week + 1 == $dis2->start_week)
//                    {
//                        DisabledDays::create([
//                            'user_id' => Auth::id(),
//                            'start_week' => $dis1->start_week,
//                            'end_week' => $dis2->end_week,
//                            'weekday' => $dis1->weekday,
//                            'start_year' => $dis1->start_year,
//                            'end_year' => $dis2->end_year,
//                        ]);
//                        $dis1->delete();
//                        $dis2->delete();
//                    }
//                }
//            }
//        }

        return back()->with(['message' => ['message' => 'Er is iets fout gegaan.', 'type' => 'danger']]);
    }
}
