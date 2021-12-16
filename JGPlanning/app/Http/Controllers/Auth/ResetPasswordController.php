<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller {

    public function getPassword($token) {

        return view('Auth.passwords.reset', ['token' => $token]);
    }

    public function updatePassword(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required',

        ]);

        $updatePassword = DB::table('password_resets')
            ->where(['email' => $request->email, 'token' => $request->hiddentoken])
            ->first();
        if(!$updatePassword)
            return back()->with(['message'=>['message' => 'Ongeldige Token!', 'type' => 'danger']]);

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        Mail::send('Auth.password_changed', ['user' => $user], function($message) use($request){
            $message->to($request->email);
            $message->subject('Password has been reset');
        });

        return redirect()->route('login')->with(['message'=>['message' => 'Wachtwoord aangepast', 'type' => 'success']]);

    }
}
