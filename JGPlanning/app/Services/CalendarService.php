<?php

namespace App\Services;

use App\Models\Availability;

class CalendarService
{
    public function generateCalendarData($weekDays)
    {
        $calendarData = [];
        $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start'), config('app.calendar.end'));
        $lessons   = Availability::all();

        foreach ($timeRange as $time)
        {
            $timeText = $time['start'] . ' - ' . $time['end'];
            $calendarData[$timeText] = [];

            foreach ($weekDays as $index => $day)
            {
                $lesson = $lessons->where('weekdays', $index)->where('start', $time['start'])->first();

                if ($lesson)
                {
                    array_push($calendarData[$timeText], [
                        'class_name'   => '',
                        'teacher_name' => '',
                        'rowspan'      => $lesson->difference/30 ?? ''
                    ]);
                }
                else if (!$lessons->where('weekday', $index)->where('start', '<', $time['start'])->where('end', '>=', $time['end'])->count())
                {
                    array_push($calendarData[$timeText], 1);
                }
                else
                {
                    array_push($calendarData[$timeText], 0);
                }
            }
        }

        dd($calendarData);
        return $calendarData;
    }
}
