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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        $auth_user = Auth::user();

        $maintainer_count = User::all()->where('role_id', Role::getRoleID('maintainer'))->count();

        $validated = $request->validate([
            'huidig_wachtwoord' => ['nullable'],
            'password' => ['nullable', 'confirmed', 'max:10', 'different:current_password'],
            'roles' =>[Rule::requiredIf($auth_user['role_id'] == Role::getRoleID('maintainer'))],
            'telefoon_nummer' => ['required','regex:/^([0-9\s\-\+\(\)]*)$/','min:10','max:10', Rule::unique('users', 'phone_number')->ignore($user['id'])],
        ]);

        //checken of telefoonnummer wel begint met 06
        $number = substr($validated['phone_number'], 0, 2);
        if($number != '06'){
            return redirect()->back()->with(['message' => ['message' => 'Telefoonnummer moet beginnen met 06', 'type' => 'danger']]);
        }

        // see if the maintainer is editing himself by looking at the role id of the user who is getting edited and the user who is logged in
        if ($auth_user['role_id'] == Role::getRoleID('maintainer')) {
            if ($maintainer_count <= 1 && $auth_user['role_id'] != $validated['roles'] && $user['role_id'] == $auth_user['role_id']) {
                return redirect()->back()->with(['message' => ['message' => 'Let op! Er is nog één maintainer over! Gebruiker niet aangepast', 'type' => 'danger']]);
            }
        }

        //update profile of user

        if(empty($validated['password'])){
            $user->update([
                'role_id' => $validated['roles']??$auth_user['role_id'],
                'phone_number' => $validated['phone_number'],
            ]);
        }else{
            if(Hash::check($request->current_password, $user['password'])){
                $user->update([
                    'password' => Hash::make($validated['password']),
                    'role_id' => $validated['roles']??$auth_user['role_id'],
                    'phone_number' => $validated['phone_number'],
                ]);
            }else{
                return redirect()->back()->with(['message' => ['message' => 'Gegevens incorrect', 'type' => 'danger']]);
            }
        }

        return redirect()->back()->with(['message' => ['message' => 'Gebruiker succesvol bewerkt', 'type' => 'success']]);
    }
}
