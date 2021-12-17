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

//      And here's the mess I call 'code'
        foreach ($dates as $da)
        {
            foreach ($data as $d)
            {
                $date_start = $date
                    ->setISODate($d->start_year, $d->start_week)
                    ->addDays($d->weekdays - 1)
                    ->format('Y-m-d');
                $date_end = $date
                    ->setISODate($d->end_year, $d->end_week)
                    ->addDays($d->weekdays - 1)
                    ->format('Y-m-d');
                if ($da->dayOfWeek == $d->weekdays)
                {
                  if (($da->format('Y-m-d') >= $date_start) && ($da->format('Y-m-d') <= $date_end))
                    {
                        if ($d->finalized)
                        {
                            if ($d->comment)
                            {
                                $events[] = Calendar::event(
                                    substr($d->start_time, 0, -3) . " - " . substr($d->end_time, 0, -3). ": " . $d->comment,
                                    true,
                                    $da->format('Y-m-d'),
                                    $da->format('Y-m-d'). '- 1 day',
                                    null,
                                    [
                                        'color' => '#CB6827',
                                        'textColor' => 'white',
                                        'url' => '/rooster/disable_days/' . $da->weekOfYear . '/' . $da->year . '/' . $da->dayOfWeek . '/'. $user_id .'/'
                                    ]
                                );
                            }
                            else
                            {
                                $events[] = Calendar::event(
                                    substr($d->start_time, 0, -3) . " - " . substr($d->end_time, 0, -3). ": Geen opmerking",
                                    true,
                                    $da->format('Y-m-d'),
                                    $da->format('Y-m-d'). '- 1 day',
                                    null,
                                    [
                                        'color' => '#CB6827',
                                        'textColor' => 'white',
                                        'url' => '/rooster/disable_days/' . $da->weekOfYear . '/' . $da->year . '/' . $da->dayOfWeek . '/'. $user_id .'/'
                                    ]
                                );
                            }
                        }
                        else
                        {
                            if ($d->comment)
                            {
                                $events[] = Calendar::event(
                                    substr($d->start_time, 0, -3) . " - " . substr($d->end_time, 0, -3). ": " . $d->comment ,
                                    true,
                                    $da->format('Y-m-d'),
                                    $da->format('Y-m-d'). '- 1 day',
                                    null,
                                    [
                                        'color' => '1C88A4',
                                        'textColor' => 'white',
                                        'url' => '/rooster/disable_days/' . $da->weekOfYear . '/' . $da->year . '/' . $da->dayOfWeek . '/'. $user_id .'/'
                                    ]
                                );
                            }
                            else
                            {
                                $events[] = Calendar::event(
                                    substr($d->start_time, 0, -3) . " - " . substr($d->end_time, 0, -3). ": Geen opmerking",
                                    true,
                                    $da->format('Y-m-d'),
                                    $da->format('Y-m-d'). '- 1 day',
                                    null,
                                    [
                                        'color' => '1C88A4',
                                        'textColor' => 'white',
                                        'url' => '/rooster/disable_days/' . $da->weekOfYear . '/' . $da->year . '/' . $da->dayOfWeek . '/'. $user_id .'/'
                                    ]
                                );
                            }

                        }
                    }
                }
            }
        }

        foreach ($dates as $da)
        {
            foreach ($disdays as $dis)
            {
                $date_dis_start = $date
                    ->setISODate($dis->start_year, $dis->start_week)
                    ->addDays($dis->weekday - 1)
                    ->format('Y-m-d');
                $date_dis_end = $date
                    ->setISODate($dis->end_year, $dis->end_week)
                    ->addDays($dis->weekday - 1)
                    ->format('Y-m-d');
                if ($da->dayOfWeek == $dis->weekday)
                {
                    if (($da->format('Y-m-d') >= $date_dis_start) && ($da->format('Y-m-d') <= $date_dis_end))
                    {
                        for ($i = 0; $i < count($events); $i++)
                        {
                            if ($events[$i]->start->format('Y-m-d') == $da->format('Y-m-d'))
                            {
                                if ($dis->by_admin == true)
                                {
                                    $events[$i] = Calendar::event(
                                        'Dag Uitgezet door admin',
                                        true,
                                        $da->format('Y-m-d'),
                                        $da->format('Y-m-d'). '- 1 day',
                                        null,
                                        [

                                            'color' => 'lightgray',
                                            'textColor' => 'black',
                                            'url' => '/rooster/disable_days/' . $da->weekOfYear . '/' . $da->year . '/' . $da->dayOfWeek . '/'.$dis->id .'/'
                                        ]
                                    );
                                }
                                else
                                {
                                    if ($dis->finalized)
                                    {
                                        $events[$i] = Calendar::event(
                                            'Dag uitgezet en vastgezet',
                                            true,
                                            $da->format('Y-m-d'),
                                            $da->format('Y-m-d'). '- 1 day',
                                            null,
                                            [
                                                'color' => 'lightgray',
                                                'textColor' => 'black',
                                                'url' => '/rooster/disable_days/' . $da->weekOfYear . '/' . $da->year . '/' . $da->dayOfWeek . '/'.$dis->id .'/'
                                            ]
                                        );
                                    }
                                    else
                                    {
                                        $events[$i] = Calendar::event(
                                            'Dag uitgezet',
                                            true,
                                            $da->format('Y-m-d'),
                                            $da->format('Y-m-d'). '- 1 day',
                                            null,
                                            [
                                                'color' => 'lightgray',
                                                'textColor' => 'black',
                                                'url' => '/rooster/disable_days/' . $da->weekOfYear . '/' . $da->year . '/' . $da->dayOfWeek . '/'.$dis->id .'/'
                                            ]
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
//        dd($events);
        return \Calendar::addEvents($events)->setOptions(['lang' => 'nl', 'hiddenDays' => [0]]);
    }
}
