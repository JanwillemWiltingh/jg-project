<?php

namespace App\Http\Controllers\Admin;

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
        $users = User::all();
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
        $roles = Role::all();
        $user_session = Auth::user();
        return view('admin/users/create')->with(['roles'=>$roles, 'user_session' => $user_session]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['required','unique:users,email'],
            'password' => ['required', 'confirmed'],
            'roles' =>['required'],
        ]);

        $current_user = Auth::user();
        if($current_user['role_id'] == 1){
            $validated['roles'] = 2;
        }
        $user = new User;
        $user['name'] = $validated['name'];
        $user['email'] = $validated['email'];
        $user['password'] = Hash::make($validated['password']);
        $user['role_id'] = $validated['roles'];
        $user->save();

        return redirect()->route('admin.users.index')->with(['message'=>'User created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Application|Factory|View
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user_session = Auth::user();
        return view('admin/users/edit')->with(['user' => $user, 'roles' => $roles, 'user_session' => $user_session]);

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

        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['required', Rule::unique('users','email')->ignore($user['id'])],
            'password' => ['nullable'],
            'password_confirmation' => ['nullable'],
            'roles' =>['required'],
        ]);

        if($validated['password'] != $validated['password_confirmation']){
            return redirect()->back()->with(["message"=>"Passwords don't match"]);
        }

        //  Make sure an admin can't change an employee
        if($auth_user['role_id'] == 1){
            $validated['roles'] = 2;
        }

        $user_id = $auth_user['id'];
        $user_db = User::all()->where('email', $auth_user['email'])->first();
        $maintainer_count = User::all()->where('role_id', 3)->count();

        //see if the maintainer is editing himself
        if($maintainer_count <= 1 && $user_id == $user_db['id']){
            return redirect()->back()->with(['message'=>'WAARSCHUWING!!! Er is nog één maintainer over! Role niet aangepast']);
        }

        if(empty($validated['password'])){
            $user->update(['name' => $validated['name'], 'email' => $validated['email'], 'role_id' => $validated['roles']]);
        }else{
            $user->update(['name' => $validated['name'], 'email' => $validated['email'], 'password' => Hash::make($validated['password']), 'role_id' => $validated['roles']]);
        }
        return redirect()->back()->with(['message'=>'User updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        if(empty($user['deleted_at'])){
            $now = new DateTime();
            $user->update(['deleted_at' => $now]);
            return redirect()->back()->with(['message'=>'User deleted successfully']);
        }else{
            $user->update(['deleted_at' => NULL]);
            return redirect()->back()->with(['message'=>'User un-deleted successfully']);
        }
        //return view('admin/users/destroy')->with(['user'=>$user]);
    }
}
