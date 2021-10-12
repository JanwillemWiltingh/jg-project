@extends('layouts.app')

@section('content')
    <h1>Edit User</h1>
    @if(session()->get('message')) {{ session()->get('message') }} @endif
    <form method="get" action="{{ route('admin.users.update', $user['id']) }}">
        <h6>Name</h6>
        <label>
            <input style="width: 300px; height: 35px; font-size: 20px;" type="text" name="name" placeholder="Name" value="{{$user['name']}}">
            @if($errors->has('name'))
                <div class="error">{{ $errors->first('name') }}</div>
            @endif
        </label>
        <h6>Email</h6>
        <label>
            <input style="width: 300px; height: 35px; font-size: 20px;" type="email" name="email" placeholder="Email" value="{{$user['email']}}">
            @if($errors->has('email'))
                <div class="error">{{ $errors->first('email') }}</div>
            @endif
        </label>
        <h6>New Password</h6>
        <label>
            <input style="width: 300px; height: 35px; font-size: 20px;" type="password" name="password" placeholder="Password">
            @if($errors->has('password'))
                <div class="error">{{ $errors->first('password') }}</div>
            @endif
        </label>
        <h6>Confirm New Password</h6>
        <label>
            <input style="width: 300px; height: 35px; font-size: 20px;" type="password" name="password_confirmation" placeholder="Confirm Password">
            @if($errors->has('password_confirmation'))
                <div class="error">{{ $errors->first('password_confirmation') }}</div>
            @endif
        </label><hr>
        <label style="color: black; font-size: 20px;">What role does the user get?</label>
        <select name="roles" style="display: block; width: 100px;">
            @foreach($roles as $role)
                <option value="{{$role['id']}}" @if($role['id'] == $user['role_id'] || old('roles') == $role['id']) selected @endif>{{$role['name']}}</option>
            @endforeach
            @if($errors->has('roles'))
                <div class="error">{{ $errors->first('roles') }}</div>
            @endif
        </select><br>
        <input style="display: block;" type="submit" value="Save">
    </form>
@endsection
