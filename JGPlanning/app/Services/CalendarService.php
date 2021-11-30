<?php

namespace App\Services;

use App\Models\{DisabledDays, Rooster, User};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class CalendarService
{
    public function generateCalendarData($weekDays, $userID, $week_number, $year)
    {
        $date = Carbon::now();

        $final_date = $date->setISODate($year, $week_number);
        $calendarData = [];
        $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start'), config('app.calendar.end'));

        $lessons   = Rooster::all()
            ->where('user_id', $userID);

        $array1 = [];
        $array2 = [];


        for ($i = 0; $i < count($weekDays); $i++)
        {
            if ($i == 0) {
                array_push($array2, $final_date->format('Y-m-d'));
            }
            else
            {
                array_push($array2, $final_date->addDays(1)->format('Y-m-d'));
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
                $array1 = [];
                $disabled_array = [];
                $disabled_days = DisabledDays::all()
                    ->where('user_id', $userID)
                    ->where('weekday', $index);

                $disID = null;
                foreach ($disabled_days as $dis)
                {
                    if ($dis->weekday == 1)
                    {
                        $final_dis_date_start = $date
                            ->setISODate($dis->start_year, $dis->start_week)
                            ->format('Y-m-d');
                        $final_dis_date_end = $date
                            ->setISODate($dis->end_year, $dis->end_week)
                            ->format('Y-m-d');
                    }
                    else
                    {
                        $final_dis_date_start = $date
                            ->setISODate($dis->start_year, $dis->start_week)
                            ->addDays($dis->weekday)
                            ->format('Y-m-d');
                        $final_dis_date_end = $date
                            ->setISODate($dis->end_year, $dis->end_week)
                            ->addDays($dis->weekday)
                            ->format('Y-m-d');
                    }

                    if (($array2[$index - 1] > $final_dis_date_start) && ($array2[$index - 1] < $final_dis_date_end)) {
                        $disID = $dis->id;
                    }
                }

                $lesID = null;

                foreach ($lessons->where('weekdays', $index) as $les)
                {
                    if ($index == 1) {
                        $final_db_date_start = $date
                            ->setISODate($les->start_year, $les->start_week)
                            ->format('Y-m-d');
                        $final_db_date_end = $date
                            ->setISODate($les->end_year, $les->end_week)
                            ->format('Y-m-d');
                    }
                    else
                    {
                        $final_db_date_start = $date
                            ->setISODate($les->start_year, $les->start_week)
                            ->addDays(1)
                            ->format('Y-m-d');
                        $final_db_date_end = $date
                            ->setISODate($les->end_year, $les->end_week)
                            ->addDays(1)
                            ->format('Y-m-d');
                    }
                    if (($array2[$index - 1] >= $final_db_date_start) && ($array2[$index - 1] <= $final_db_date_end))
                    {
                        $lesID = $les->id;
                    }
                }
                $lesson = $lessons
                    ->where('id', $lesID)
                    ->where('weekdays', $index)
                    ->where('start_time', $time['start']. ":00")
                    ->first();

                if($lesson)
                {
                    $start = substr($lesson->start_time, "0", "5");
                    $end = substr($lesson->end_time, "0", "5");
                }

                if ($disID)
                {
                    if($timeText == "08:00 - 08:30")
                    {
                        if ($disabled_days->where('id', $disID)->first())
                        {
                            if (strlen($disabled_days->where('id', $disID)->first()->start_week) == 1)
                            {
                                $start_week = "0" . $disabled_days->where('id', $disID)->first()->start_week;
                            }
                            else
                            {
                                $start_week = $disabled_days->where('id', $disID)->first()->start_week;
                            }

                            if (strlen($disabled_days->where('id', $disID)->first()->end_week) == 1)
                            {
                                $end_week = "0" . $disabled_days->where('id', $disID)->first()->end_week;
                            }
                            else
                            {
                                $end_week = $disabled_days->where('id', $disID)->first()->end_week;
                            }
                            array_push($calendarData[$timeText], [
                                'rowspan'      => 20,
                                'from_home'    => "",
                                'comment'      => "Uitgezet door admin.",
                                'start_time'   => $disabled_days->where('id', $disID)->first()->start_year."-W".$start_week,
                                'end_time'     => $disabled_days->where('id', $disID)->first()->end_year."-W".$end_week,
                                'by_admin'     => $disabled_days->where('id', $disID)->first()->by_admin,
                                'disabled_id'  => $disID,
                                'id'           => $lesID,
                            ]);
                        }
                        else
                        {
                            array_push($calendarData[$timeText], [
                                'rowspan'      => 20,
                                'from_home'    => "",
                                'comment'      => "Uitgezet door admin",
                                'start_time'   => "",
                                'end_time'     => "",
                                'by_admin'     => 0,
                                'disabled_id'  => "",
                                'id'           => "",
                            ]);
                        }
                    }
                    else
                    {
                        array_push($calendarData[$timeText], 0);
                    }
                }
                else if ($lesson)
                {
                    array_push($calendarData[$timeText], [
                        'rowspan' => Carbon::parse(Carbon::createFromFormat('H:i:s', $lesson['end_time'])->format('H:i:s'))->diffInMinutes($time_start) /30 ?? '',
                        'from_home' => $lesson['from_home'],
                        'comment' => $lesson['comment'],
                        'start_time' => $start,
                        'end_time' => $end,
                        'id' => $lesson['id']
                    ]);
                }

                else if (!$lessons->where('id', $lesID)->where('start_time', '<=',$time['start']. ":00")->where('end_time', '>=',$time['end']. ":00")->first())
                {
                    array_push($calendarData[$timeText], 1);
                }
                else
                {
                    array_push($calendarData[$timeText], 0);
                }
            }
        }
//        dd($calendarData);
        return $calendarData;
    }
}
