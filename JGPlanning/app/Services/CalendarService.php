<?php

namespace App\Services;

use App\Models\{DisabledDays, Rooster, User};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class CalendarService
{
    public function generateCalendarData($weekDays, $userID, $week_number)
    {
        $calendarData = [];
        $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start'), config('app.calendar.end'));

        $lessons   = Rooster::all()->where('user_id', $userID);
        $lessons   = Rooster::where('user_id', $userID)->first();


        $user = User::find($userID);

        $array1 = [];
        $disabled_array = [];
        $disabled_days = DisabledDays::all()
            ->where('user_id', $userID)
            ->where('start_week', '<=', $week_number)
            ->where('end_week', '>=', $week_number)
            ->sortBy('weekday');

        $disabled_count = count($disabled_days);

        foreach ($disabled_days as $dd)
        {
            array_push($array1, $dd->weekday);
        }

        for ($i = 0; $i < 7; $i++)
        {
            if (in_array($i, $array1))
            {
                array_push($disabled_array, 1);
            }
            else
            {
                array_push($disabled_array, null);
            }
        }
        foreach ($timeRange as $time)
        {
            $timeText = $time['start'] . ' - ' . $time['end'];
            $calendarData[$timeText] = [];

            $time_start = $time['start']. ':00';
            $time_end = $time['end']. ':00';


            foreach ($weekDays as $index => $day)
            {

                if (isEmpty($lessons))
                {
                    $lesson = $lessons
                        ->where('weekdays', $index)
                        ->where('start_time', $time_start)
                        ->where('start_week', '<=', $week_number)
                        ->where('end_week', '>=', $week_number)
                        ->first();
                }
                else
                {
                    $lesson = null;
                }

                if($lesson)
                {
                    $start = substr($lesson->start_time, "0", "5");
                    $end = substr($lesson->end_time, "0", "5");
                }

                if ($disabled_array)
                {
                    if ($disabled_array[$index])
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

                    else if (!$lessons->where('weekdays', $index)->where('start_time', '<', $time['start'])->where('end_time', '>=', $time['end'])->where('start_week', '<=', $week_number)->where('end_week', '>=', $week_number)->count())
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
