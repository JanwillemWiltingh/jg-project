<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Rooster;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use DateTime;
use phpDocumentor\Reflection\Types\Null_;
use Psy\Util\Str;

class UserController extends Controller
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
    public function index()
    {
        $users = User::all()->where('deleted_at', '=', null);
        $deleted_users = User::all()->where('deleted_at', '!=', null);
        $user_session = Auth::user();
        $roles = Role::all();

        return view('admin/users/index')->with(['users'=>$users, 'deleted_users' => $deleted_users, 'user_session' => $user_session, 'roles'=>$roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $user_session = Auth::user();
        $roles = Role::all();

        return view('admin/users/create')->with(['roles'=>$roles, 'user_session' => $user_session]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'firstname' => ['required'],
            'middlename' => ['nullable'],
            'lastname' => ['required'],
            'email' => ['required','unique:users,email'],
            'roles' =>['required'],
            'phone_number' => ['required','regex:/^([0-9\s\-\+\(\)]*)$/','min:10', 'unique:users,phone_number'],
        ]);

        $current_user = Auth::user();
        if($current_user['role_id'] == Role::getRoleID('admin')){
            $validated['roles'] = Role::getRoleID('employee');
        }
        //checken of telefoonnummer wel begint met 06
        $number = substr($validated['phone_number'], 0, 2);
        if($number != '06'){
            return redirect()->back()->with(['message' => ['message' => 'Telefoonnummer moet beginnen met 06', 'type' => 'danger']]);
        }
        //create random string of 20 for password
        $password = \Illuminate\Support\Str::random(20);
        User::create([
            'firstname' => ucfirst($validated['firstname']),
            'middlename' => ($validated['middlename']),
            'lastname' => ucfirst($validated['lastname']),
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role_id' => $validated['roles'],
            'phone_number' => $validated['phone_number']
        ]);
        Mail::send('Auth.user', ['request' => $request], function($message) use($request){
            $message->to($request->email);
            $message->subject('Nieuwe gebruiker JG Planning');
        });
        return redirect()->route('admin.users.index')->with(['message'=>['message' => 'Gebruiker succesvol Aangemaakt', 'type' => 'success']]);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Application|Factory|View
     */
    public function show(User $user)
    {
        $user_session = Auth::user();
        return view('admin/users/show')->with(['user' => $user, 'user_session' => $user_session]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Application|Factory|View|RedirectResponse
     * @throws Exception
     */
    public function edit(User $user)
    {

        $user_session = Auth::user();
        if($user['role_id'] == Role::getRoleID('maintainer')){
            return redirect()->route('admin.users.index')->with(['message'=> ['message' => 'Helaas gaat dit niet', 'type' => 'danger']]);
        }
        if(!empty($user['deleted_at'])){
            return redirect()->route('admin.users.index')->with(['message'=> ['message' => 'Kan een gebruiker niet aanpassen als het account gedeactiveerd is', 'type' => 'danger']]);
        }
        $roles = Role::all();

        return view('admin/users/edit')->with(['user' => $user, 'roles' => $roles, 'user_session' => $user_session]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return Application|Factory|View|RedirectResponse
     * @throws Exception
     */
    public function update(Request $request, User $user)
    {
        $auth_user = Auth::user();
        $users = User::all();

        $maintainer_count = User::all()->where('role_id', Role::getRoleID('maintainer'))->count();

        $validated = $request->validate([
            'firstname' => ['required', 'string'],
            'middlename' => ['nullable', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['required', Rule::unique('users','email')->ignore($user['id'])],
            'roles' =>['required'],
            'phone_number' => ['required','regex:/^([0-9\s\-\+\(\)]*)$/','min:10','max:10', Rule::unique('users', 'phone_number')->ignore($user['id'])],
        ]);
        //checken of telefoonnummer wel begint met 06
        $number = substr($validated['phone_number'], 0, 2);
        if($number != '06'){
            return redirect()->back()->with(['message' => ['message' => 'Telefoonnummer moet beginnen met 06', 'type' => 'danger']]);
        }

        //  see if the maintainer is editing himself by looking at the role id of the user who is getting edited and the user who is logged in
        if($maintainer_count <= 1 && $auth_user['role_id'] != $validated['roles'] && $user['role_id'] == $auth_user['role_id']){
            return redirect()->back()->with(['message'=> ['message' => 'Let op! Er is nog één maintainer over! Gebruiker niet aangepast', 'type' => 'danger']]);
        }

        $user->update([
            'firstname' => ucfirst($validated['firstname']),
            'middlename' => $validated['middlename'],
            'lastname' => ucfirst($validated['lastname']),
            'email' => $validated['email'],
            'role_id' => $validated['roles'],
            'phone_number' => $validated['phone_number']
        ]);

        return redirect()->route('admin.users.index')->with(['message' => ['message' => 'Gebruiker succesvol bewerkt', 'type' => 'success']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(User $user): RedirectResponse
    {
        $user_session = Auth::user();
//        $maintainer_count = User::all()->where('role_id', '=', Role::getRoleID('maintainer', ))->count();
        $maintainer_count = User::all()
                    ->where('role_id', '=', Role::getRoleID('maintainer'))
                    ->where('deleted_at', '=', null)->count();
        //  see if the maintainer is editing himself by looking at the role id of the user who is getting edited and the user who is logged in
        if($maintainer_count <= 1  && $user['role_id'] == $user_session['role_id']){
            return redirect()->back()->with(['message'=> ['message' => 'Error', 'type' => 'danger']]);
        }
        if($user['role_id'] == Role::getRoleID('admin') && $user_session['role_id'] == Role::getRoleID('admin')){
            return redirect()->route('admin.users.index')->with(['message'=> ['message' => 'Helaas gaat dit niet', 'type' => 'danger']]);
        }
        if($user['role_id'] == Role::getRoleID('maintainer') && $user_session['role_id'] == Role::getRoleID('maintainer')){
            return redirect()->route('admin.users.index')->with(['message'=> ['message' => 'Helaas gaat dit niet', 'type' => 'danger']]);
        }
        if(empty($user['deleted_at'])){
            $now = new DateTime();
            $user->update(['deleted_at' => $now]);
            return redirect()->route('admin.users.index')->with(['message'=>['message' => 'Gebruiker succesvol verwijderd!', 'type' => 'success']]);
        }else{
            $user->update(['deleted_at' => NULL]);
            return redirect()->route('admin.users.index')->with(['message'=>['message' => 'Gebruiker succesvol hersteld!', 'type' => 'success']]);
        }
    }
}
