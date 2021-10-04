<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clock;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $clocks = Clock::where('date', Carbon::now()->toDateString())->paginate(15);
        return view('admin.clock-in.index')->with(['clocks' => $clocks]);
    }

    /**
     * Display the specified resource.
     *
     * @param Clock $clock
     * @return Application|Factory|View
     */
    public function show(Clock $clock)
    {
        return view('admin.clock-in.show')->with(['clock' => $clock]);
    }
}
