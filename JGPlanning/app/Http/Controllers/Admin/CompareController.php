<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CompareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $users = User::all();
        $all_users = User::all();
        $month = Carbon::now()->year.'-'.Carbon::now()->month;
        $weeks = Carbon::now()->year.'-W'.Carbon::now()->week;

        $input_field = 'month';
        if($request->all() != []) {
            $validated = $request->validate([
                'month' => ['required'],
                'weeks' => ['required'],
                'user' => ['required'],
                'date-format' => ['required'],
            ]);

            $request->session()->flash('month', $validated['month']);
            $request->session()->flash('weeks', $validated['weeks']);
            $request->session()->flash('user', $validated['user']);
            $request->session()->flash('date-format', $validated['date-format']);

            if($validated['user'] != 0) {
                $users = User::where('id', $validated['user'])->get();
            }

            $input_field = $validated['date-format'];
        }

        return view('admin.compare.index')->with([
            'users' => $users,
            'all_users' => $all_users,
            'month' => $month,
            'weeks' => $weeks,
            'input_field' => $input_field
        ]);
    }
}
