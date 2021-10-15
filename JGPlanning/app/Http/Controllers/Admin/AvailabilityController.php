<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Clock;
use App\Models\Rooster;
use App\Models\User;
use App\Services\CalendarService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $users = User::where('role_id', 2)->get();
        return view('admin.availability.index', compact(
            'users',
        ));
    }

    public function index_rooster()
    {
        $users = User::where('role_id', 2)->get();
        return view('admin.availability.calender.table', compact(
            'users',
        ));
    }

    public function user_availability($user, CalendarService $calendarService)
    {
        $user = User::find($user);
        $isRooster    = false;
        $availability = Availability::where('user_id', $user)->get();
        $weekDays     = Availability::WEEK_DAYS;
        $calendarData = $calendarService->generateCalendarData($weekDays, $user, $isRooster);
        return view('admin.availability.calender.index', compact(
            'weekDays',
            'calendarData',
            'availability',
            'user'
        ));
    }

    public function user_rooster(CalendarService $calendarService, $user)
    {
        $user_info = User::find($user);
        $isRooster    = true;
        $availability = Rooster::where('user_id', $user)->get();
        $weekDays     = Availability::WEEK_DAYS;
        $calendarData = $calendarService->generateCalendarData($weekDays, $user_info->id, $isRooster);

        return view('admin.rooster.index', compact(
            'weekDays',
            'calendarData',
            'availability',
            'user',
            'user_info'
        ));
    }
}
