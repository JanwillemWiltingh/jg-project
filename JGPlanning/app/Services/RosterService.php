<?php

namespace App\Services;

use Carbon\Carbon;
use DateTime;
use App\Models\{DisabledDays, Rooster, User};
use LaravelFullCalendar\Calendar;

class RosterService
{
    public function generateRosterData($user_id): Calendar
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

                $events[] = Calendar::event(
                    'title',
                    true,
                    $final_date_start,
                    $final_date_end
                );
            }
        }

        $roster_data = Calendar::addEvents($events);
        dd($roster_data);
        return Calendar::addEvents($events);
    }
}
