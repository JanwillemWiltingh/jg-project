<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Clock;
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
        $users = User::all();
        return view('admin.availability.index', compact(
            'users',
        ));
    }

    public function user_availability($user, CalendarService $calendarService)
    {
        dd($user);
        $weekDays     = Availability::WEEK_DAYS;
        $calendarData = $calendarService->generateCalendarData($weekDays, $user);

        return view('admin.rooster.index', compact(
            'weekDays',
            'calendarData'
        ));
    }
}
