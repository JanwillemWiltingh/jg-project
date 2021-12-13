<?php

namespace App\Services;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Date;
use LaravelFullCalendar\Calendar;
use Carbon\Carbon;
use DateTime;
use App\Models\{Availability, DisabledDays, Rooster, User};

class RosterService
{
    public function generateRosterData($user_id)
    {
//      Gets the current date and creates a array for all the variables to go into.
        $date = Carbon::now();
        $events = [];

//      Database data.
        $data = Rooster::all()
            ->where('user_id', $user_id);
        $disdays = DisabledDays::all()
            ->where('user_id', $user_id);

//      Gets every day from this year
        $period = CarbonPeriod::create(Carbon::parse(date('Y-m-d'))->startOfYear(), Carbon::parse(date('Y-m-d'))->endOfYear());

//      Convert the period to an array of dates
        $dates = $period->toArray();

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

                $start_date = Carbon::parse($date_start);
                $end_date = Carbon::parse($date_end);
                if ($da->dayOfWeek == $d->weekdays)
                {
                  if (in_array($da->dayOfYear, range($start_date->dayOfYear, $end_date->dayOfYear)))
                    {
                        $events[] = Calendar::event(
                            substr($d->start_time, 0, -3) . " - " . substr($d->end_time, 0, -3),
                            true,
                            $da->format('Y-m-d'),
                            $da->format('Y-m-d'). '- 1 day',
                            null,
                            [
                                'color' => '1C88A4',
                                'textColor' => 'white',
                                'url' => '/rooster/disable_days/' . $da->weekOfYear . '/' . $da->year . '/' . $da->dayOfWeek . '/'
                            ]
                        );
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

//                dd(, $date_dis_start);
//                $start_dis_date = Carbon::parse($date_dis_start);
//                $end_dis_date = Carbon::parse($date_dis_end);
                if ($da->dayOfWeek == $dis->weekday)
                {
                    if (($da->format('Y-m-d') >= $date_dis_start) && ($da->format('Y-m-d') <= $date_dis_end))
                    {
                        for ($i = 0; $i < count($events); $i++)
                        {
                            if ($events[$i]->start->format('Y-m-d') == $da->format('Y-m-d'))
                            {
                                $events[$i] = Calendar::event(
                                    'Dag Uitgezet',
                                    true,
                                    $da->format('Y-m-d'),
                                    $da->format('Y-m-d'). '- 1 day',
                                    null,
                                    [
                                        'color' => 'lightgray',
                                        'textColor' => 'black',
                                        'url' => '/rooster/disable_days/' . $da->weekOfYear . '/' . $da->year . '/' . $da->dayOfWeek . '/'
                                    ]
                                );
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
