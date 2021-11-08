<?php

namespace App\Services;

use Carbon\Carbon;

class TimeService
{
    public function generateTimeRange($from, $to)
    {
        $time = Carbon::parse($from);
        $timeRange = [];

        do
        {
            array_push($timeRange, [
                'start' => $time->format("H:i"),
                'end' => $time->addMinutes(30)->format("H:i")
            ]);
        } while ($time->format("H:i") !== $to);

        return $timeRange;
    }

    public function roundTime($time, $round_to)
    {
        $start_round = $round_to*60;
        $start_rounded = round(strtotime($time) / $start_round) * $start_round;
        return date("H:i:s", $start_rounded);
    }
}
