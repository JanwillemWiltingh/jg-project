<?php

namespace App\Services;

use App\Models\Availability;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarService
{
    public function generateCalendarData($weekDays)
    {
        $calendarData = [];
        $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start'), config('app.calendar.end'));
        $lessons   = Availability::where('user_id', Auth::user()->id)->get();

        foreach ($timeRange as $time)
        {
            $timeText = $time['start'] . ' - ' . $time['end'];
            $calendarData[$timeText] = [];

            $time_start = $time['start']. ':00';
            $time_end = $time['start']. ':00';
            foreach ($weekDays as $index => $day)
            {
                $lesson = $lessons->where('weekdays', $index)->where('start', $time_start)->first();

                if ($lesson)
                {
                    array_push($calendarData[$timeText], [
                        'class_name'   => '',
                        'teacher_name' => '',
                        'rowspan'      => Carbon::parse(Carbon::createFromFormat('H:i:s', $lesson['end'])->format('H:i:s'))->diff($time_start)->format('%H') * 2
                    ]);
                }
                else if (!$lessons->where('weekdays', $index)->where('start','<', $time_start)->where('end', '>', $time_end)->count())
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
