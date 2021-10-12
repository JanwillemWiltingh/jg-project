<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckIfIsInWeek
{
    public function CheckInWeek($beginDate, $endDate)
    {
        if($beginDate->startOfWeek()->format('Y-m-d') == $endDate->startOfWeek()->format('Y-m-d'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
