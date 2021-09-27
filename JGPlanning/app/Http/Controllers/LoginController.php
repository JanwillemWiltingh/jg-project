<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
//            dd(User::where('name', $validated['name'])->get()->first());
            $roles = User::where('email', $validated['email'])->get()->first()->role()->get();

            if($roles->search('admin') !== TRUE) {
                return redirect()->route('admin.users.index')->with('msg', 'Signed in');
            } else {
                return redirect()->route('home')->with('msg', 'Signed in');
            }

//            return redirect()->intended('dashboard')->withSuccess('Signed in');
        }

        return redirect()->back()->withErrors(['status' =>'Login details are not valid']);
    }
}
