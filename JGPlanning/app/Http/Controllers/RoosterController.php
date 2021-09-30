<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\User;
use App\Services\CalendarService;
use Illuminate\Http\Request;

class RoosterController extends Controller
{
    public function index(CalendarService $calendarService)
    {
        $weekDays     = Availability::WEEK_DAYS;
        $calendarData = $calendarService->generateCalendarData($weekDays);

        return view('user.rooster.index', compact(
            'weekDays',
            'calendarData'
        ));
    }
}
