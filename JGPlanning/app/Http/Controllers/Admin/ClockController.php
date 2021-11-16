<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CollectionPagination;

class ClockController extends Controller
{
    //  Checks if user is logged in
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        //  Get current date and all users
       $now = Carbon::now()->toDateString();
       $users = User::all();

        //  Get all clocks and paginate
        $clocks = Clock::where('date', $now)->paginate(15);

        if($request->all() != []) {
            //  Validate the data from the input fields
            $validated = $request->validate([
                'date' => ['required', 'date'],
                'user' => ['required', 'int']
            ]);

            //  When successfully validated flash this data back to the session
            $request->session()->flash('date', $validated['date']);
            $request->session()->flash('user', $validated['user']);

            //  Put the validated user in a variable or the When function breaks
            $user = $validated['user'];

            //  Get a new collection
            $clocks = Clock::all()->where('date', $validated['date'])->when($validated['user'] != 0, function ($query, $user) {
                return $query->where('user_id', $user);
            });

            //  Paginate the collection
            $clocks = (new CollectionPagination)->paginate($clocks, 10, request('page'), ['path' => 'clock']);
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
            $end_time = Carbon::now()->addHours(Clock::ADD_HOURS);
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
        $clock->update([
           'start_time' => $validated['start_time'],
           'end_time'   => $validated['end_time'],
        ]);
        return redirect()->back()->with(['message'=> ['message' => 'Uren aangepast', 'type' => 'success']]);
    }
}

