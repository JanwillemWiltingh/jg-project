<?php

namespace App\Services;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use LaravelFullCalendar\Calendar;
use Carbon\Carbon;
use DateTime;
use App\Models\{Availability, DisabledDays, Rooster, User};

class RosterService
{
    public function generateRosterData($user_id)
    {
        $rooster_array = [];
        $disabled_array = [];

//      Gets the current date and creates a array for all the events to go into.
        $date = Carbon::now();
        $events = [];

//      Get all weekdays
        $weekdays = Availability::WEEK_DAYS_MOB;

//      Database data.
        $data = Rooster::all()
            ->where('user_id', $user_id);
        $disdays = DisabledDays::all()
            ->where('user_id', $user_id);

//      Gets every day from this year
        $days_of_year = CarbonPeriod::create(Carbon::parse(date('Y-m-d'))->startOfYear(), Carbon::parse(date('Y-m-d'))->endOfYear());

//      Convert it all to an array of dates
        $dates = $days_of_year->toArray();

        foreach ($data as $d)
        {
            $date_rooster = $date
                ->setISODate($d->start_year, $d->week)
                ->addDays($d->weekday - 1)
                ->format('Y-m-d');
            array_push($rooster_array, $date_rooster);
        }

        foreach ($disdays as $dis)
        {
            $date_dis = $date
                ->setISODate($dis->start_year, $dis->start_week)
                ->addDays($dis->weekday - 1)
                ->format('Y-m-d');
            array_push($disabled_array, $date_dis);
        }


//      And here's the mess I call 'code'
        foreach($dates as $da)
        {
            if (in_array($da->format('Y-m-d'), $disabled_array))
            {
                $disabled = $disdays
                    ->where('user_id', $user_id)
                    ->where('start_year', $da->year)
                    ->where('start_week', $da->weekOfYear)
                    ->where('weekday', $da->dayOfWeek)
                    ->first();
                if ($disabled)
                {
                    if ($disabled->finalized)
                    {
                        $comment = "Dag uitgezet en vastgezet.";
                    }
                    else if($disabled->by_admin)
                    {
                        $comment = "Dag uitgezet door admin.";
                    }
                    else
                    {
                        $comment = "Dag uitgezet.";
                    }
                }
                else
                {
                    $comment = "Error.";
                }
                $events[] = Calendar::event(
                    $comment,
                    true,
                    $da->format('Y-m-d'),
                    $da->format('Y-m-d'),
                    null,
                    [
                        'color' => 'lightgray',
                        'textColor' => 'black',
                        'url' => '/rooster/disable_days/' . $da->weekOfYear . '/' . $da->year . '/' . $da->dayOfWeek . '/'. $user_id .'/'
                    ]
                );
            }
            else if (in_array($da->format('Y-m-d'), $rooster_array))
            {
                $rooster = $data
                    ->where('user_id', $user_id)
                    ->where('start_year', $da->year)
                    ->where('week', $da->weekOfYear)
                    ->where('weekday', $da->dayOfWeek)
                    ->first();
                if ($rooster)
                {
                    if ($rooster->comment)
                    {
                        $comment =  substr($rooster->start_time, 0, -3).' - ' . substr($rooster->end_time, 0, -3) . ': ' . $rooster->comment;
                    }
                    else
                    {
                        $comment = substr($rooster->start_time, 0, -3). ' - ' . substr($rooster->end_time, 0, -3) . ': Geen opmerking';
                    }

                    if ($rooster->finalized)
                    {
                        $color = "#CB6827";
                    }
                    else
                    {
                        $color = "#1C88A4";
                    }
                }
                else
                {
                    $comment = "Error";
                    $color = "Lightgray";
                }

                $events[] = Calendar::event(
                    $comment,
                    true,
                    $da->format('Y-m-d'),
                    $da->format('Y-m-d'),
                    null,
                    [
                        'color' => $color,
                        'textColor' => 'white',
                        'url' => '/rooster/disable_days/' . $da->weekOfYear . '/' . $da->year . '/' . $da->dayOfWeek . '/'. $user_id .'/'
                    ]
                );
            }
        }
//        dd($events);
        return \Calendar::addEvents($events)->setOptions(['lang' => 'nl', 'hiddenDays' => [0]]);
    }
}
