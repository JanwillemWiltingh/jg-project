<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $user = Auth::user();
        $role_id = $user['role_id'];
        if($role_id == 2){
            abort(403);
        }else {
            $users = User::all();
            return view('admin/users/index')->with(['users' => $users]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin/users/create')->with(['roles'=>$roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['required','unique:users,email'],
            'password' => ['required'],
            'password_confirmation' => ['required'],
            'roles' =>['required'],
        ]);
        if($validated['password'] != $validated['password_confirmation']){
            return redirect()->back()->with(["message"=>"Passwords don't match"]);
        }
        $user = new User;
        $user['name'] = $validated['name'];
        $user['email'] = $validated['email'];
        $user['password'] = Hash::make($validated['password']);
        $user['role_id'] = $validated['roles'];
        $user->save();

        return redirect()->back()->with(['message'=>'User created successfully']);



//        User::create([
//           'name' => $validated['name'],
//           'email' => $validated['email'],
//           'password' => Hash::make($validated['password']),
//            'role_id' => $validated['roles'],
//        ]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function destroy(User $user)
    {
        $users = User::all();
        return view('admin/users/destroy')->with(['users'=>$users]);
    }
}
