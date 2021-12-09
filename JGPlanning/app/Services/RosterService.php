<?php

namespace App\Services;

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

        $weekdays = Availability::WEEK_DAYS;
        $date = Carbon::now();
        $events = [];
        $array = [];
        $array_test = [
            ['id' => 'yeas'],
            ['id' => 'nah']
        ];
        $data = Rooster::all()
            ->sortBy('weekdays')
            ->where('user_id', $user_id);
        $disdays = DisabledDays::all()
            ->where('user_id', $user_id);
//        if($data->count()){
//            foreach ($data as $d)
//            {
//                $final_date_start = $date
//                    ->setISODate($d->start_year, $d->start_week)
//                    ->addDays($d->weekday - 1)
//                    ->format('Y-m-d');
//                $final_date_end = $date
//                    ->setISODate($d->end_year, $d->end_week)
//                    ->addDays($d->weekday - 1)
//                    ->format('Y-m-d');
//                foreach ($data as $d2)
//                {
//                    $final_date_2_start = $date
//                        ->setISODate($d2->start_year, $d2->start_week)
//                        ->addDays($d2->weekday - 1)
//                        ->format('Y-m-d');
//                    $final_date_2_end = $date
//                        ->setISODate($d2->end_year, $d2->end_week)
//                        ->addDays($d->weekday - 1)
//                        ->format('Y-m-d');
//                    if (!Carbon::parse($final_date_2_start)->between($final_date_start, $final_date_end))
//                    {
//                        $final_start = Carbon::parse($final_date_start)->addDay();
//                        $events[0] = Calendar::event(
//                            'Geen opmerking',
//                            false,
//                            $final_start->format('Y-m-d'),
//                            $final_date_2_end. '+ 1 day',
//                            $d->weekdays,
//                        );
//                    }
//                    elseif (!Carbon::parse($final_date_2_end)->between($final_date_start, $final_date_end))
//                    {
//                        $final_end = Carbon::parse($final_date_start)->addDays(-1);
//                        $events[0] = Calendar::event(
//                            'Geen opmerking',
//                            false,
//                            $final_date_start,
//                            $final_end. '+ 1 day',
//                            $d->weekdays,
//                        );
//                    }
//                    else
//                    {
//                        $events[0] = Calendar::event(
//                            'Geen opmerking',
//                            false,
//                            $final_date_start,
//                            $final_date_end. '+ 1 day',
//                            $d->weekdays,
//                        );
//                    }
//                }
//            }
//        }
        if($data->count()){
            $events[] = Calendar::event(
                'U bent ingeroosterd op deze dagen.',
                false,
                Carbon::parse(\date("Y-m-d"))->startOfYear(),
                Carbon::parse(\date("Y-m-d"))->endOfYear(),
                null,
                [
                    'color' => '#1C88A4',
                    'textColor' => 'white'
                ]
            );
        }
        $i = 0;
        foreach ($disdays as $dis)
        {
            $i++;
            $final_date_start = $date
                ->setISODate($dis->start_year, $dis->start_week)
                ->addDays($dis->weekday - 1)
                ->format('Y-m-d');
            $final_date_end = $date
                ->setISODate($dis->end_year, $dis->end_week)
                ->addDays($dis->weekday - 1)
                ->format('Y-m-d');
            $events[$i] = Calendar::event(
                'Er zijn dagen hier voor u uitgezet',
                true,
                $final_date_start,
                $final_date_end,
                null,
                [
                    'color' => 'lightgrey',
                    'textColor' => 'black'
                ]
            );
        }
//        foreach ($disdays as $dis)
//        {
//            $final_date_start = $date
//                ->setISODate($dis->start_year, $dis->start_week)
//                ->addDays($dis->weekday - 1)
//                ->format('Y-m-d');
//            $final_date_end = $date
//                ->setISODate($dis->end_year, $dis->end_week)
//                ->addDays($dis->weekday - 1)
//                ->format('Y-m-d');
//            $events[0] = Calendar::event(
//                'dag uitgezet',
//                false,
//                $final_date_start,
//                $final_date_end,
//                null,
//            );
//        }
//        foreach ($data as $d)
//        {
//            array_push($array, $d->comment);
//        }

//        dd($events);
        return \Calendar::addEvents($events)->setOptions(['lang' => 'nl', 'hiddenDays' => [0]]);
    }
}
