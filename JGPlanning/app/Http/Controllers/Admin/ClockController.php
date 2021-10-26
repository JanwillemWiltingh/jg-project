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
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {

       $clocks = Clock::where('date', Carbon::now()->toDateString())->paginate(15);
       $now = Carbon::now()->toDateString();
       $users = User::all();

        if($request->all() != []) {
            $validated = $request->validate([
                'date' => ['required'],
                'user' => ['required']
            ]);

            $request->session()->flash('date', $validated['date']);
            $request->session()->flash('user', $validated['user']);

            if($validated['user'] != 0) {
                $clocks = Clock::where('date', $validated['date'])
                    ->where('user_id', $validated['user'])
                    ->paginate(15);
            } else {
                $clocks = Clock::where('date', $validated['date'])
                    ->paginate(15);
            }
        }
        return view('admin.clock-in.index')->with(['clocks' => $clocks, 'now' => $now, 'users' => $users]);
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

