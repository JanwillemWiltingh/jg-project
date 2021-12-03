<?php

namespace App\Services;

use LaravelFullCalendar\Calendar;
use Carbon\Carbon;
use DateTime;
use App\Models\{DisabledDays, Rooster, User};

class RosterService
{
    public function generateRosterData($user_id)
    {
        $date = Carbon::now();
        $events = [];
        $data = Rooster::all()
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
                        $d->comment,
                        true,
                        $final_date_start,
                        $final_date_end. '+ 1 day',
                        null,
                    );
                }
                else
                {
                    $events[] = Calendar::event(
                        'Geen opmerking',
                        true,
                        $final_date_start,
                        $final_date_end. '+ 1 day',
                        null,
                    );
                }
            }
        }
        return \Calendar::addEvents($events);
    }
}
