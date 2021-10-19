<?php

namespace App\Services;

use App\Models\{Rooster, User};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isNull;

class CalendarService
{
    public function generateCalendarData($weekDays, $userID, $isRooster)
    {
        $calendarData = [];
        $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start'), config('app.calendar.end'));

        $lessons   = Rooster::where('user_id', $userID)->get();

        $user = User::find($userID);



        foreach ($timeRange as $time)
        {
            $timeText = $time['start'] . ' - ' . $time['end'];
            $calendarData[$timeText] = [];

            $time_start = $time['start']. ':00';
            $time_end = $time['end']. ':00';
            foreach ($weekDays as $index => $day)
            {
                $lesson = $lessons->where('weekdays', $index)->where('start_time', $time_start)->first();

                if($lesson)
                {
                    $start = substr($lesson->start_time, "0", "5");;
                    $end = substr($lesson->end_time, "0", "5");;
                }

                if ($lesson)
                {
//                    if((json_decode($user->unavailable_days)[$index - 1]) == "on" || $lesson->disabled == 1)
//                    {
//                        array_push($calendarData[$timeText], 2);
//                    }
//                    else
//                    {
                        array_push($calendarData[$timeText], [
                            'rowspan'      => Carbon::parse(Carbon::createFromFormat('H:i:s', $lesson['end_time'])->format('H:i:s'))->diff($time_start)->format('%H') * 2,
                            'from_home'    => $lesson['from_home'],
                            'comment'      => $lesson['comment'],
                            'start_time'   => $start,
                            'end_time'     => $end,
                        ]);
//                    }
                }
                else if (!$lessons->where('weekdays', $index)->where('start_time','<', $time_start)->where('end_time', '>=', $time_end)->count())
                {
                    array_push($calendarData[$timeText], 1);
                }
                else if ((json_decode($user->unavailable_days)[$index]) == "on")
                {
                    array_push($calendarData[$timeText], 2);
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
