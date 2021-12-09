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
        $weekdays = Availability::WEEK_DAYS;
        $date = Carbon::now();
//        dd(in_array($date->dayOfYear, range(Carbon::parse('2021-02-15')->dayOfYear, Carbon::parse('2021-12-15')->dayOfYear)));
        $events = [];
        $events2 = [];
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
        $period = CarbonPeriod::create(Carbon::parse(date('Y-m-d'))->startOfYear(), Carbon::parse(date('Y-m-d'))->endOfYear());

        // Convert the period to an array of dates
        $dates = $period->toArray();


        $date_final = null;
        $date_final_array = [];

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

//                $days = [];
//
                $start_date = Carbon::parse($date_start);
                $end_date = Carbon::parse($date_end);
                if ($da->dayOfWeek == $d->weekdays)
                {
                    if (in_array($da->dayOfYear, range($start_date->dayOfYear, $end_date->dayOfYear)))
                    {
                        $events[] = Calendar::event(
                            'Op deze dag bent u ingeroosterd',
                            true,
                            $da->format('Y-m-d'),
                            $da->format('Y-m-d'),
                            null,
                            [
                                'textColor' => 'white'
                            ]
                        );
                    }
                }
            }
        }
//        dd($events);
        return \Calendar::addEvents($events)->setOptions(['lang' => 'nl', 'hiddenDays' => [0]]);
    }
}
