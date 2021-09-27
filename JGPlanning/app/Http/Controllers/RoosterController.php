<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoosterController extends Controller
{
    public function index() {
        return view('user.rooster.index');
    }
}
