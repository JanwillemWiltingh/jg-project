<?php

namespace App\Services;

use Carbon\Carbon;

class GetWeekdayDate
{
    public function WeekDate($weekDay, $week)
    {
//        Gets the beginning of the week based on the date you passed with the function
        $weekbegin = Carbon::now()->startOfWeek();

        if($weekDay == 0)
        {
            $weekd = $weekbegin->addDays($weekDay);
        }
        else
        {
            $weekd = $weekbegin->addDays($weekDay - 1);
        }

//        Adds the weeks
        $output = $weekd->addWeeks($week)->format('Y-m-d');

        return $output;
    }
}
