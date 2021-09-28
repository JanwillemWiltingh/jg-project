<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Clock;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\False_;
use phpDocumentor\Reflection\Types\True_;

class ClockerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $user = Auth::user();
        $clock = Clock::all()->where('user_id', $user['id'])->where('time', '>=', date('Y-m-d').' 00:00:00')->last();
        return view('user.clock-in.index')->with(['start' => $clock['start'] ?? False]);
    }

    /**
     * @return RedirectResponse
     */
    public function clock(): RedirectResponse
    {
        $user = Auth::user();
        $clocks = Clock::all()->where('user_id', $user['id'])->where('time', '>=', date('Y-m-d').' 00:00:00');

        if($clocks->count() === 0){
            Clock::create([
                'time' => Carbon::now()->addHours(2)->toDateTimeString(),
                'start' => True,
                'comment' => 'Start of Day',
                'user_id' => $user['id'],
            ]);
        } else {
            $clock = $clocks->last();
            Clock::create([
                'time' => Carbon::now()->addHours(2)->toDateTimeString(),
                'start' => !$clock['start'],
                'comment' => 'Start of Day',
                'user_id' => $user['id'],
            ]);
        }

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
