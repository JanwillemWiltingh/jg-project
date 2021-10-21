<?php

namespace App\Services;

use App\Models\{Rooster, User};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isNull;

class CalendarService
{
    public function generateCalendarData($weekDays, $userID)
    {
        $calendarData = [];
        $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start'), config('app.calendar.end'));

        $lessons   = Rooster::where('user_id', $userID)->first();


        $user = User::find($userID);

        foreach ($timeRange as $time)
        {
            $timeText = $time['start'] . ' - ' . $time['end'];
            $calendarData[$timeText] = [];

            $time_start = $time['start']. ':00';
            $time_end = $time['end']. ':00';
            foreach ($weekDays as $index => $day)
            {
                if($lessons)
                {
                    $lesson = $lessons->where('weekdays', $index)->where('start_time', $time_start)->first();
                }
                else
                {
                    $lesson = "";
                }
                if($lesson)
                {
                    $lesson = $lessons->where('weekdays', $index)->where('start_time', $time_start)->first();
                    $start = substr($lesson->start_time, "0", "5");
                    $end = substr($lesson->end_time, "0", "5");
                }

                if (json_decode($user->unavailable_days))
                {
                    if (json_decode($user->unavailable_days)[$index - 1] == "on")
                    {
                        if($timeText == "08:00 - 08:30")
                        {
                            array_push($calendarData[$timeText], [
                                'rowspan'      => 20,
                                'from_home'    => "",
                                'comment'      => "Disabled",
                                'start_time'   => "",
                                'end_time'     => "",
                            ]);
                        }
                        else
                        {
                            array_push($calendarData[$timeText], 0);
                        }
                    }
                    else if ($lesson)
                    {
                        array_push($calendarData[$timeText], [
                            'rowspan' => Carbon::parse(Carbon::createFromFormat('H:i:s', $lesson['end_time'])->format('H:i:s'))->diff($time_start)->format('%H') * 2,
                            'from_home' => $lesson['from_home'],
                            'comment' => $lesson['comment'],
                            'start_time' => $start,
                            'end_time' => $end,
                        ]);
                    }

                    else if (!$lessons->where('weekdays', $index)->where('start_time', '<', $time['start'])->where('end_time', '>=', $time['end'])->count())
                    {
                        array_push($calendarData[$timeText], 1);
                    }

                    else
                    {
                        array_push($calendarData[$timeText], 0);
                    }
                }
            }
        }
//        dd($calendarData);
        return $calendarData;
    }
}
