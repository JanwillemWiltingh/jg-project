<?php

namespace App\Services;

use App\Models\Availability;
use App\Models\Rooster;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarService
{
    public function generateCalendarData($weekDays, $userID, $isRooster)
    {
        $calendarData = [];
        $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start'), config('app.calendar.end'));

        if($isRooster)
        {
            $lessons   = Rooster::where('user_id', $userID)->get();
        }
        else
        {
            $lessons   = Availability::where('user_id', $userID)->get();
        }
        foreach ($timeRange as $time)
        {
            $timeText = $time['start'] . ' - ' . $time['end'];
            $calendarData[$timeText] = [];

            $time_start = $time['start']. ':00';
            $time_end = $time['end']. ':00';
            foreach ($weekDays as $index => $day)
            {
                $lesson = $lessons->where('weekdays', $index)->where('start', $time_start)->first();


                if($lesson)
                {
                    $start = substr($lesson->start, "0", "5");;
                    $end = substr($lesson->end, "0", "5");;
                }

                if ($lesson)
                {
                    array_push($calendarData[$timeText], [
                        'rowspan'      => Carbon::parse(Carbon::createFromFormat('H:i:s', $lesson['end'])->format('H:i:s'))->diff($time_start)->format('%H') * 2,
                        'from_home'    => $lesson['from_home'],
                        'comment'      => $lesson['comment'],
                        'start_time'   => $start,
                        'end_time'     => $end,
                    ]);
                }
                else if (!$lessons->where('weekdays', $index)->where('start','<', $time_start)->where('end', '>=', $time_end)->count())
                {
                    array_push($calendarData[$timeText], 1);
                }
                else
                {
                    array_push($calendarData[$timeText], 0);
                }
            }
        }
        return $calendarData;
    }
}
