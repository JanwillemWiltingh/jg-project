<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Services\CalendarService;
use Illuminate\Http\Request;

class RoosterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(CalendarService $calendarService)
    {
        $weekDays     = Availability::WEEK_DAYS;
        $calendarData = $calendarService->generateCalendarData($weekDays);

        return view('users.rooster.index', compact(
            'weekDays',
            'calendarData'
        ));
    }
}
