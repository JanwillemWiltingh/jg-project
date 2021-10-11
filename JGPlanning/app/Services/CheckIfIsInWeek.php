<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckIfIsInWeek
{
    public function CheckInWeek($beginDate, $endDate)
    {
        return $beginDate . " " . $endDate;
    }
}
