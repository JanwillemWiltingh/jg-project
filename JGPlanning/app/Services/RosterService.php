<?php

namespace App\Services;

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
        $data = Rooster::all()
            ->sortBy('weekdays')
            ->where('user_id', $user_id);
        $disdays = DisabledDays::all()
            ->where('user_id', $user_id);
        if($data->count()){
            foreach ($data as $d)
            {
                $final_date_start = $date
                    ->setISODate($d->start_year, $d->start_week)
                    ->addDays($d->weekday - 1)
                    ->format('Y-m-d');
                $final_date_end = $date
                    ->setISODate($d->end_year, $d->end_week)
                    ->addDays($d->weekday - 1)
                    ->format('Y-m-d');

                if ($d->comment)
                {
                    $events[] = Calendar::event(
                        $weekdays[$d->weekdays]. ": " .$d->comment,
                        true,
                        $final_date_start,
                        $final_date_end. '+ 1 day',
                        null,
                    );
                }
                else
                {
                    $events[] = Calendar::event(
                        $weekdays[$d->weekdays]. ': Geen opmerking',
                        true,
                        $final_date_start,
                        $final_date_end. '+ 1 day',
                        null,
                    );
                }
            }
//            foreach ($disdays as $di)
//            {
//                $final_date_start = $date
//                    ->setISODate($di->start_year, $di->start_week)
//                    ->addDays($di->weekday - 1)
//                    ->format('Y-m-d');
//                $final_date_end = $date
//                    ->setISODate($di->end_year, $di->end_week)
//                    ->addDays($d->weekday - 1)
//                    ->format('Y-m-d');
//
//
//                $events[] = Calendar::event(
//                    ''
//                    true,
//                    $final_date_start,
//                    $final_date_end. '+ 1 day',
//                    null,
//                );
//            }
        }
//        dd(\Calendar::addEvents($events)->setOptions(['lang' => 'nl']));
        return \Calendar::addEvents($events)->setOptions(['lang' => 'nl']);
    }
}
