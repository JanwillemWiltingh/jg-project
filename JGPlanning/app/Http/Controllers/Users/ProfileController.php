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
            'firstname' => ['required', 'string'],
            'middlename' => ['nullable', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['required', Rule::unique('users','email')->ignore($user['id'])],
            'roles' =>[Rule::requiredIf($auth_user['role_id'] == Role::getRoleID('maintainer'))],
        ]);


        //  see if the maintainer is editing himself by looking at the role id of the user who is getting edited and the user who is logged in
        if ($auth_user['role_id'] == Role::getRoleID('maintainer')) {
            if ($maintainer_count <= 1 && $auth_user['role_id'] != $validated['roles'] && $user['role_id'] == $auth_user['role_id']) {
                return redirect()->back()->with(['message' => ['message' => 'Let op! Er is nog één maintainer over! Gebruiker niet aangepast', 'type' => 'danger']]);
            }
        }
        $user->update([
            'firstname' => ucfirst($validated['firstname']),
            'middlename' => $validated['middlename'],
            'lastname' => ucfirst($validated['lastname']),
            'email' => $validated['email'],
            'role_id' => $validated['roles']??$auth_user['role_id'],
        ]);
        return redirect()->back()->with(['message' => ['message' => 'Gebruiker succesvol Bewerkt', 'type' => 'success']]);
    }
}
