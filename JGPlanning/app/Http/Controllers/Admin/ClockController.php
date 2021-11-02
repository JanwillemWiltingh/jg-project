<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
       $user_session = Auth::user();
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
        return view('admin.clock-in.index')->with(['clocks' => $clocks, 'now' => $now, 'users' => $users, 'user_session' => $user_session]);
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

    /**
     * @param Clock $clock
     * @return Application|Factory|View
     */
    public function edit(Clock $clock){
        $user_session = Auth::user();
        $start_time = $clock['start_time'];
        $end_time = $clock['end_time'];
        $start_time = Carbon::parse($start_time);
        if(empty($end_time)){
            $end_time = Carbon::now()->addHours(2);
        }else{
            $end_time = Carbon::parse($end_time);
        }
        $total_difference_in_seconds = $end_time->diffInSeconds($start_time);
        $total_difference_in_hours = $total_difference_in_seconds / 3600;
        return view('admin/clock-in/edit')->with(['user_session' => $user_session, 'clock' => $clock, 'total_difference' => $total_difference_in_hours]);
    }

    /**
     * @param Clock $clock
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Clock $clock, Request $request ): RedirectResponse
    {
        $validated = $request->validate([
            'start_time' => ['required'],
            'end_time' => ['required'],
        ]);
//        calculate the total hours in seconds
//        database total difference
//        $clock_by_id = DB::table('clocker')->where('id', $clock['id'])->first();
//        $start_time = $clock_by_id->start_time;
//        $end_time = $clock_by_id->end_time;
//
//        $start_time = Carbon::parse($start_time);
//        if(empty($end_time)){
//            $end_time = Carbon::now()->addHours(2);
//        }else{
//            $end_time = Carbon::parse($end_time);
//        }
//        $total_difference_in_seconds = $end_time->diffInSeconds($start_time);
//        $total_difference_in_hours = $total_difference_in_seconds / 3600;
//        $total_difference_r = round($total_difference_in_hours, 2);
//        //request total difference
//        $rStart = $request['start_time'];
//        $rEnd = $request['end_time'];
//        $rTotal = $request['total_hours'];
//        $rStart = Carbon::parse($rStart);
//        if(empty($rEnd)){
//            $rEnd = Carbon::now()->addHours(2);
//        }else{
//            $rEnd = Carbon::parse($rEnd);
//        }
//        //see if the start_time hasn't changed and the total hours did, so we can update the end time
//        if($request['end_time'] == $clock['end_time'] && $request['start_time'] == $clock['start_time'] && $validated['total_hours'] != $total_difference_r){
//            $end_time->add(new DateInterval('PT'.$total_difference_in_seconds.''));
//        }
//        dd($request['end_time'], $clock['end_time'], $request['total_hours'], $total_difference_r);

        $clock->update([
           'start_time' => $validated['start_time'],
           'end_time'   => $validated['end_time'],

        ]);

        return redirect()->back()->with(['message'=> ['message' => 'Uren aangepast', 'type' => 'success']]);
    }
}

