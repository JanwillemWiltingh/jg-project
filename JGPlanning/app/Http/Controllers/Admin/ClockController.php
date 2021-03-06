<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clock;
use App\Models\User;
use App\Services\TimeService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CollectionPagination;
use DateTime;

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
                'datum' => ['required', 'date'],
                'gebruiker' => ['required', 'int']
            ]);

            //  When successfully validated flash this data back to the session
            $request->session()->flash('date', $validated['datum']);
            $request->session()->flash('user', $validated['gebruiker']);

            //  Get all the clocks
            $clocks = Clock::all()->where('date', $validated['datum']);
            if($validated['gebruiker'] != 0) {
                $clocks = $clocks->where('user_id', $validated['gebruiker']);
            }

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
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit(Clock $clock){
        //  Get the logged in user
        $user_session = Auth::user();

        //  Get the start and end_time
        $start_time = $clock['start_time'];
        $end_time = $clock['end_time'];

        //  Parse the start_time
        $start_time = Carbon::parse($start_time);

        if(!empty($clock['deleted_at'])){
            return redirect()->route('admin.clock.index')->with(['message'=> ['message' => 'Kan een kloktijd niet aanpassen als de tijden gedeactiveerd zijn', 'type' => 'danger']]);
        }
        //  if end_time is set parse it else use the current time
        if(empty($end_time)){
            $end_time = Carbon::now()->addHours(Clock::ADD_HOURS);
        }else{
            $end_time = Carbon::parse($end_time);
        }

        //  Calculate the difference for the start and end time
        $total_difference_in_seconds = $end_time->diffInSeconds($start_time);
        $total_difference_in_hours = $total_difference_in_seconds / 3600;

        return view('admin/clock-in/edit')->with(['user_session' => $user_session, 'clock' => $clock, 'total_difference' => $total_difference_in_hours]);
    }

    /**
     * @param Clock $clock
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Clock $clock, Request $request, TimeService $time): RedirectResponse
    {
        $date = $clock['date'];
        //  Validate the end and start time and update them
        $valitated = $request->validate([
            'start_tijd_uren' => ['required'],
            'start_tijd_minuten' => ['required'],
            'eind_tijd_uren' => ['required'],
            'eind_tijd_minuten' => ['required']
        ]);

        $time_start = $time->roundTime(Carbon::createFromFormat('H:i', $valitated['start_tijd_uren']. ":".$valitated['start_tijd_minuten'])->format('H:i:s'), '15');
        $time_end = $time->roundTime(Carbon::createFromFormat('H:i', $valitated['eind_tijd_uren']. ":".$valitated['eind_tijd_minuten'])->format('H:i:s'), '15');

        if ($time_start > $time_end)
        {
            return redirect()->route('admin.clock.index')->with(['message'=> ['message' => 'De ingevulde begin tijd is later dan de ingevulde eind tijd', 'type' => 'danger'], 'date' => $date]);
        }

        $clock->update([
            'start_time' => $time_start,
            'end_time' => $time_end,
        ]);

        return redirect()->route('admin.clock.index')->with(['message'=> ['message' => 'Uren aangepast', 'type' => 'success'], 'date' => $date]);
    }

    /**
     * @param Clock $clock
     * @return RedirectResponse
     */
    public function destroy(Clock $clock): RedirectResponse
    {
        if(empty($clock['deleted_at'])){
            $now = new DateTime();
            $clock->update(['deleted_at' => $now]);
            return redirect()->back()->with(['message'=>['message' => 'Uren succesvol gedeactiveerd!', 'type' => 'success']]);
        }else{
            $clock->update(['deleted_at' => NULL]);
            return redirect()->back()->with(['message'=>['message' => 'Uren succesvol geactiveerd!', 'type' => 'success']]);
        }
    }
}

