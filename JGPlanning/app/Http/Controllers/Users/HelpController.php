<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function help(){
        $user = Auth::user();
        return view('help.index')->with(['user' => $user]);
    }
}
