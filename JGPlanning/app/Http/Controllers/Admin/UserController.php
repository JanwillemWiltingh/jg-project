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
        $users = User::sortable()->paginate(5);
        $user_session = Auth::user();

        return view('admin/users/index')->with(['users'=>$users, 'user_session' => $user_session]);
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
        ]);

        $current_user = Auth::user();
        if($current_user['role_id'] == Role::getRoleID('admin')){
            $validated['roles'] = Role::getRoleID('employee');
        }

        $newUser = User::create([
            'firstname' => ucfirst($validated['firstname']),
            'middlename' => ($validated['middlename']),
            'lastname' => ucfirst($validated['lastname']),
            'email' => $validated['email'],
            'password' => Hash::make('welkom1203@'),
            'role_id' => $validated['roles'],
        ]);
        Mail::send('Auth.user', ['user' => $user], function($message) use($request){
            $message->to($request->email);
            $message->subject('Nieuwe Gebruiker JG Planning');
        });

        for ($i = 1; $i < 6; $i++)
        {
            Rooster::create([
                'user_id' => $newUser->id,
                'start_time' => '08:30:00',
                'end_time' => '17:00:00',
                'comment' => "",
                'from_home' => 0,
                'weekdays' => $i,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
                'year' => date('Y'),
            ]);
        }

//        return redirect()->route('admin.users.index')->with(['message'=>['message' => 'User created successfully', 'type' => 'success']]);
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
        if($user['role_id'] == Role::getRoleID('maintainer') || $user['role_id'] == Role::getRoleID('admin') && $user_session['role_id'] == Role::getRoleID('admin')){
            return redirect()->route('admin.users.index')->with(['message'=> ['message' => 'Helaas gaat dit niet', 'type' => 'danger']]);
        }
        $roles = Role::all();

        return view('admin/users/edit')->with(['user' => $user, 'roles' => $roles, 'user_session' => $user_session]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $auth_user = Auth::user();

        $maintainer_count = User::all()->where('role_id', Role::getRoleID('maintainer'))->count();

        $validated = $request->validate([
            'firstname' => ['required'],
            'middlename' => ['nullable'],
            'lastname' => ['required'],
            'email' => ['required', Rule::unique('users','email')->ignore($user['id'])],
            'roles' =>['required'],
        ]);


        //  see if the maintainer is editing himself by looking at the role id of the user who is getting edited and the user who is logged in
        if($maintainer_count <= 1 && $auth_user['role_id'] != $validated['roles'] && $user['role_id'] == $auth_user['role_id']){
            return redirect()->back()->with(['message'=> ['message' => 'Let op! Er is nog één maintainer over! Gebruiker niet aangepast', 'type' => 'danger']]);
        }

        //  When the admin edit's a user set the role to 2
        if($auth_user['role_id'] == Role::getRoleID('admin')){
            $validated['roles'] = Role::getRoleID('employee');
        }

        $user->update([
            'firstname' => ucfirst($validated['firstname']),
            'middlename' => $validated['middlename'],
            'lastname' => ucfirst($validated['lastname']),
            'email' => $validated['email'],
            'role_id' => $validated['roles'],
        ]);
        return redirect()->back()->with(['message' => ['message' => 'Gebruiker succesvol Bewerkt', 'type' => 'success']]);
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
        if(empty($user['deleted_at'])){
            $now = new DateTime();
            $user->update(['deleted_at' => $now]);
            return redirect()->route('admin.users.index')->with(['message'=>['message' => 'Gebruiker succesvol Verwijderd!', 'type' => 'success']]);
        }else{
            $user->update(['deleted_at' => NULL]);
            return redirect()->route('admin.users.index')->with(['message'=>['message' => 'Gebruiker succesvol Hersteld!', 'type' => 'success']]);
        }
    }
}
