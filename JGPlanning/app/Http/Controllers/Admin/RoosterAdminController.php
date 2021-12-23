<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\DisabledDays;
use App\Models\Role;
use App\Models\Rooster;
use App\Models\User;
use App\Services\CalendarService;
use App\Services\RosterService;
use App\Services\TimeService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RoosterAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Functie om gebruikers hun roosters te zien.
     *
     * @return Application|Factory|View
     */
    public function index_rooster()
    {
        $plan_in = false;

        $allow = false;

        foreach (User::all()->where('role_id', Role::getRoleID('employee')) as $user)
        {
            if ($user->roosters->where('start_year', date('Y') + 1)->count() == 0)
            {
                $allow = true;
            }
        }

        if (Carbon::now()->month == 12)
        {
            if ($allow)
            {
                $plan_in = true;
            }
        }

        $users = User::all();

        return view('admin.availability.calender.table', compact(
            'users',
            'plan_in'
        ));
    }

    function plan_rooster_for_user($id, $min)
    {
        $user = User::all()
            ->where('id', $id)
            ->first();
        if ($user->roosters->where('start_year', date('Y') + 1 - $min)->count() == 0)
        {
            for ($x = 1 - $min; $x <= 2; $x++)
            {
                for ($a = 1; $a <= 52; $a++)
                {
                    for ($i = 1; $i < 7; $i++)
                    {
                        Rooster::create([
                            'start_time' => '08:30:00',
                            'end_time' => '17:00:00',
                            'comment' => "",
                            'from_home' => 0,
                            'weekdays' => $i,
                            'user_id' => $id,
                            'week' => $a,
                            'year' => date('Y') + $x,
                        ]);
                    }
                }
            }
        }
    }

    function disable_days_for_user($id, $min)
    {
        $disabled = DisabledDays::all()
            ->where('id', $id);
        if ($disabled->where('start_year', date('Y') + 1 - $min)->count() == 0)
        {
            for ($x = 1 - $min; $x <= 2; $x++)
            {
                for ($a = 1; $a <= 52; $a++)
                {
                    Rooster::create([
                        'weekdays' => 6,
                        'user_id' => $id,
                        'start_week' => $a,
                        'end_week' => $a,
                        'start_year' => date('Y') + $x,
                        'end_year' => date('Y') + $x,
                    ]);
                }
            }
        }
    }

    /**
     * Functie voor admins om naar gebruikers hun rooster te kijken.
     *
     * @param CalendarService $calendarService
     * @param $user
     * @param $week
     * @return Application|Factory|View
     */
    public function user_rooster(RosterService $rosterService, CalendarService $calendarService, $user, $week, $year)
    {
        if ($week > 52) {
            $targetYear = $year + 1;
            return redirect('/admin/rooster/' . $user . '/1/' . $targetYear);
        } else if ($week < 1) {
            $targetYear = $year - 1;
            return redirect('/admin/rooster/' . $user . '/52/' . $targetYear);
        }
        $user_info = User::find($user);

        $rooster = Rooster::all()
            ->where('user_id', $user);
        $checkDisabledDays = DisabledDays::all()
            ->where('user_id', $user);

        if ($checkDisabledDays->count() == 0)
        {
            $this->disable_days_for_user($user, '1');
        }
        if ($rooster->count() == 0)
        {
            $this->plan_rooster_for_user($user, '1');
        }

        $weekDays = Availability::WEEK_DAYS;

        $current_week = Carbon::now()
            ->setISODate(date('Y'), $week);

        $start_of_week = $current_week->startOfWeek()->format('d-M');
        $end_of_week = $current_week->endOfWeek()->format('d-M');

        $weekstring = $start_of_week . " - " . $end_of_week;

        $availability = Rooster::where('user_id', $user)->get();
        $calendarData = $calendarService->generateCalendarData($weekDays, $user_info->id, $week, $year);
        $roster = $rosterService->generateRosterData($user_info->id);

        return view('admin.rooster.index', compact(
            'weekDays',
            'calendarData',
            'availability',
            'user',
            'user_info',
            'weekstring',
            'roster',
        ));
    }

    /**
     * Functie om de array met de disabled dagen te sturen naar de database.
     *
     * @param $user
     * @param Request $request
     * @return RedirectResponse
     */
    public function push_days($user, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'data' => ['required', 'array']
        ]);


        for ($i = 1; $i < 6; $i++) {
            if ($validated['data'][$i - 1]) {
                if (Rooster::all()->where('user_id', $user)->where('weekdays', $i)->first()) {
                    $updaterooster = Rooster::where('weekdays', $i)->where('user_id', $user)->update([
                        'disabled' => true
                    ]);
                }
            } else {
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

    /**
     * functie om uitgezette datums aan te makken.
     *
     * @param Request $request
     * @param $user
     * @return RedirectResponse
     */
    public function disable_days(Request $request, $user)
    {
        $validated = $request->validate([
            'weekday' => ['required', 'integer'],
            'start_week' => ['required'],
            'end_week' => ['required']
        ]);

        $date = Carbon::now();

        $start_week = substr($validated['start_week'], '6');
        $end_week = substr($validated['end_week'], '6');

        $start_year = substr($validated['start_week'], '0', '-4');
        $end_year = substr($validated['end_week'], '0', '-4');

        if ($validated['weekday'] == 1) {
            $final_date_start = $date
                ->setISODate($start_year, $start_week)
                ->format('Y-m-d');
            $final_date_end = $date
                ->setISODate($end_year, $end_week)
                ->format('Y-m-d');
        } else {
            $final_date_start = $date
                ->setISODate($start_year, $start_week)
                ->addDays($validated['weekday'])
                ->format('Y-m-d');
            $final_date_end = $date
                ->setISODate($end_year, $end_week)
                ->addDays($validated['weekday'])
                ->format('Y-m-d');
        }

        $checkDisabled = DisabledDays::all()
            ->where('user_id', $user)
            ->where('weekday', $validated['weekday']);

        foreach ($checkDisabled as $cd) {
            if ($cd->weekday == 1) {
                $final_db_date_start = $date
                    ->setISODate($cd->start_year, $cd->start_week)
                    ->format('Y-m-d');
                $final_db_date_end = $date
                    ->setISODate($cd->end_year, $cd->end_week)
                    ->format('Y-m-d');
            } else {
                $final_db_date_start = $date
                    ->setISODate($cd->start_year, $cd->start_week)
                    ->addDays($cd->weekday)
                    ->format('Y-m-d');
                $final_db_date_end = $date
                    ->setISODate($cd->end_year, $cd->end_week)
                    ->addDays($cd->weekday)
                    ->format('Y-m-d');
            }
            if (($final_date_start >= $final_db_date_start) && ($final_date_start <= $final_db_date_end)) {
                return back()->with(['message' => ['message' => 'De weken die u heeft ingevuld overlappen met weken die al ingevuld zijn.', 'type' => 'danger']]);
            } else if (($final_date_end >= $final_db_date_start) && ($final_date_end <= $final_db_date_end)) {
                return back()->with(['message' => ['message' => 'De weken die u heeft ingevuld overlappen met weken die al ingevuld zijn.', 'type' => 'danger']]);
            }
        }

        if ($final_date_start > $final_date_end) {
            return back()->with(['message' => ['message' => 'De ingevulde begin tijd is later dan de eind tijd', 'type' => 'danger']]);
        }

        DisabledDays::create([
            'user_id' => $user,
            'weekday' => $validated['weekday'],
            'start_week' => $start_week,
            'end_week' => $end_week,
            'by_admin' => true,
            'start_year' => $start_year,
            'end_year' => $end_year
        ]);

        return back()->with(['message' => ['message' => 'De ingevulde weken zijn uitgezet.', 'type' => 'success']]);
    }

    /**
     * functie om uitgezette datums te bewerken
     *
     * @param Request $request
     * @param $user
     * @param $week
     * @return RedirectResponse
     */
    public function edit_disable_days(Request $request, $user, $week): RedirectResponse
    {
        $validated = $request->validate([
            'weekday' => ['required', 'integer'],
            'start_week' => ['required'],
            'end_week' => ['required']
        ]);
//        dd($validated);

        $start_week = substr($validated['start_week'], 6);
        $end_week = substr($validated['end_week'], 6);
//
//        $start_year = substr($validated['start_week'], -4);
//        $end_year = substr($validated['end_week'], -4);
//        dd($start_week, $end_week, $start_year, $end_year);

        $checkDisabled = DisabledDays::all()
            ->where('user_id', $user)
            ->where('weekday', $validated['weekday'])
            ->where('start_week', '<=', $week)
            ->where('end_week', '>=', $week)
            ->first();


        if ($start_week > $end_week) {
            return back()->with(['message' => ['message' => 'De ingevulde begin week is later dan de eind week', 'type' => 'danger']]);
        }

        $checkDisabled->update([
            'start_week' => $start_week,
            'end_week' => $end_week,
            'by_admin' => true
        ]);

        return back()->with(['message' => ['message' => 'De aangegeven weken zijn aangepast', 'type' => 'success']]);
    }

    /**
     * @param $user
     * @param $week
     * @param $weekday
     * @return RedirectResponse
     */
    public function delete_disable_days($user, $week, $weekday): RedirectResponse
    {
        DisabledDays::all()
            ->where('user_id', $user)
            ->where('weekday', $weekday)
            ->where('start_time', '<=', $week)
            ->where('end_week', '>=', $week)
            ->first()
            ->delete();

        return back()->with(['message' => ['message' => 'De aangegeven weken zijn verwijderd', 'type' => 'success']]);
    }

    /**
     * @param Request $request
     */
    public function manage_disable_days(Request $request)
    {
        $validate = $request->validate([
            'id' => ['required', 'integer'],
        ]);

        DisabledDays::all()
            ->where('id', $validate['id'])
            ->first()
            ->delete();
    }

    /**
     * @param Request $request
     */
    public function manage_delete_days(Request $request)
    {
        $validate = $request->validate([
            'id' => ['required', 'integer'],
        ]);

        Rooster::all()
            ->where('id', $validate['id'])
            ->first()
            ->delete();
    }

    public function plan_next_year()
    {
        $rooster = Rooster::all();
        $disabled_days = DisabledDays::all();

        $user = User::all()->where('role_id', Role::getRoleID('employee'));
        foreach ($rooster as $r)
        {
            if (Carbon::parse($r->end_year)->isPast())
            {
                $r->delete();
            }
        }

        foreach ($disabled_days as $dis)
        {
            if (Carbon::parse($dis->end_year)->isPast())
            {
                $dis->delete();
            }
        }

        foreach (User::all()->where('role_id', Role::getRoleID('employee')) as $user)
        {
            $this->plan_rooster_for_user($user->id, 0);
            $this->disable_days_for_user($user->id, 0);
        }

        return back();
    }

    public function plan_user_next_week($user)
    {
        $date = Carbon::now();

        $user_info = User::all()
            ->where('id', $user)
            ->first();

        if (Carbon::parse(date('Y-m-d'))->weekOfYear + 1 == 53)
        {
            $compare_date = 1;
            $year = Carbon::parse(date('Y-m-d'))->year + 1;
        }
        else
        {
            $compare_date = Carbon::parse(date('Y-m-d'))->weekOfYear + 1;
            $year = Carbon::parse(date('Y-m-d'))->year;
        }

        $disabled_days = DisabledDays::all()
            ->where('user_id', $user)
            ->where('start_week', $compare_date)
            ->where('start_year', $year);
        $rooster = Rooster::all()
            ->where('user_id', $user)
            ->where('week', $compare_date)
            ->where('start_year', $year);
        if ($user_info->checkIfRoosterIsSolidified(Carbon::parse(date('Y-m-d'))->addWeek()))
        {
            return back()->with(['message' => ['message' => ''. $user_info->firstname . ' ' . $user_info->middlename . ' ' . $user_info->lastname  .' Heeft geen rooster aanpassingen om vast te zetten.', 'type' => 'danger']]);
        }

        foreach ($rooster as $r)
        {
            $r->finalized = true;
            $r->update();
        }

        foreach ($disabled_days as $dis)
        {
            $dis->by_admin = false;
            $dis->finalized = true;
            $dis->update();
        }

//        Mail::send('Auth.rooster');

        return back()->with(['message' => ['message' => 'Volgende week is voor '. $user_info->firstname . ' ' . $user_info->middlename . ' ' . $user_info->lastname  .' vastgezet.', 'type' => 'success']]);
    }

    public function un_plan_user_next_week($user)
    {
        $user_info = User::all()
            ->where('id', $user)
            ->first();

        $disabled_days = DisabledDays::all()
            ->where('user_id', $user)
            ->whereIn('start_week', [Carbon::now()->weekOfYear, Carbon::now()->weekOfYear + 1]);
        $rooster = Rooster::all()
            ->where('user_id', $user)
            ->whereIn('week', [Carbon::now()->weekOfYear, Carbon::now()->weekOfYear + 1]);

        foreach ($disabled_days as $dis)
        {
            if ($dis->finalized)
            {
                $dis->finalized = false;
            }
        }

        foreach ($rooster as $r)
        {
            if ($r->finalized)
            {
                $r->finalized = false;
                $r->update();
            }
        }

        return back()->with(['message' => ['message' => 'Volgende/deze week is voor '. $user_info->firstname . ' ' . $user_info->middlename . ' ' . $user_info->lastname  .' weer opengezet.', 'type' => 'success']]);
    }
}
