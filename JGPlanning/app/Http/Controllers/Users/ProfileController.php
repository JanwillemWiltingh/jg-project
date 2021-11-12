<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function profile(){
        $roles = Role::all();
        $user = Auth::user();

        return view('profile.profile')->with(['user' => $user, 'roles' => $roles]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user_session = Auth::user();
        if ($user['email'] != $user_session['email']){
            return redirect()->back()->with(['message' => ['message' => 'Error', 'type' => 'danger']]);
        }
        return view('profile.edit')->with(['user' => $user, 'roles' => $roles]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'firstname' => ['required'],
            'middlename' => ['nullable'],
            'lastname' => ['required'],
            'email' => ['required', Rule::unique('users','email')->ignore($user['id'])],
            'current_password' => ['nullable'],
            'password' => ['nullable', 'confirmed', 'max:10', 'different:current_password'],
        ]);

        if(empty($validated['password'])){
            $user->update([
                'firstname' => $validated['firstname'],
                'middlename' => $validated['middlename'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'],
            ]);
        }else{
            if(Hash::check($request->current_password, $user['password'])){
                $user->update([
                    'firstname' => $validated['firstname'],
                    'middlename' => $validated['middlename'],
                    'lastname' => $validated['lastname'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);
            }else{
                return redirect()->back()->with(['message' => ['message' => 'Oud Wachtwoord komt niet overeen', 'type' => 'danger']]);
            }
        }
        return redirect()->back()->with(['message' => ['message' => 'Gebruiker succesvol Bewerkt', 'type' => 'success']]);
    }
}
