@extends('layouts.app')

@section('content')
    @if(session()->get('message'))
        <div class="alert alert-{{ session()->get('message')['type'] }} alert-dismissible fade show" role="alert">
            {{ session()->get('message')['message'] }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <h1>Edit User</h1>
    <form method="get" action="{{ route('admin.users.update', $user['id']) }}">
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{$user['name']}}" aria-describedby="name" placeholder="Name">

                    @if($errors->has('name'))
                        <div class="error">{{ $errors->first('name') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{$user['email']}}" aria-describedby="email" placeholder="Email">

                    @if($errors->has('email'))
                        <div class="error">{{ $errors->first('email') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" aria-describedby="password" placeholder="Password">

                    @if($errors->has('password'))
                        <div class="error">{{ $errors->first('password') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="password_confirmation">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" aria-describedby="password_confirmation" placeholder="Confirm Password">

                    @if($errors->has('password_confirmation'))
                        <div class="error">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>
            </div>
        </div>
        @if($user_session['role_id'] == $role_ids['maintainer'])
            <hr>
            <label class="black-label-text" style="font-size: 20px;">What role does the user get?</label>
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label class="black-label-text" for="roles">Roles</label>
                        <select class="form-control" name="roles" id="roles">
                            @foreach($roles as $role)
                                <option value="{{$role['id']}}" @if($role['id'] == $user['role_id'] || old('roles') == $role['id']) selected @endif>{{$role['name']}}</option>
                            @endforeach
                        </select>

                        @if($errors->has('roles'))
                            <div class="error">{{ $errors->first('roles') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <input type="hidden" name="roles" value="2">
        @endif

        <button type="submit" class="btn btn-primary" value="Save">Save</button>
    </form>





{{--    <h1>Edit User</h1>--}}
{{--    @if(session()->get('message')) {{ session()->get('message') }} @endif--}}
{{--    <form method="get" action="{{ route('admin.users.update', $user['id']) }}">--}}
{{--        <h6>Name</h6>--}}
{{--        <label>--}}
{{--            <input style="width: 300px; height: 35px; font-size: 20px;" type="text" name="name" placeholder="Name" value="{{$user['name']}}">--}}
{{--            @if($errors->has('name'))--}}
{{--                <div class="error">{{ $errors->first('name') }}</div>--}}
{{--            @endif--}}
{{--        </label>--}}
{{--        <h6>Email</h6>--}}
{{--        <label>--}}
{{--            <input style="width: 300px; height: 35px; font-size: 20px;" type="email" name="email" placeholder="Email" value="{{$user['email']}}">--}}
{{--            @if($errors->has('email'))--}}
{{--                <div class="error">{{ $errors->first('email') }}</div>--}}
{{--            @endif--}}
{{--        </label>--}}
{{--        <h6>New Password</h6>--}}
{{--        <label>--}}
{{--            <input style="width: 300px; height: 35px; font-size: 20px;" type="password" name="password" placeholder="Password">--}}
{{--            @if($errors->has('password'))--}}
{{--                <div class="error">{{ $errors->first('password') }}</div>--}}
{{--            @endif--}}
{{--        </label>--}}
{{--        <h6>Confirm New Password</h6>--}}
{{--        <label>--}}
{{--            <input style="width: 300px; height: 35px; font-size: 20px;" type="password" name="password_confirmation" placeholder="Confirm Password">--}}
{{--            @if($errors->has('password_confirmation'))--}}
{{--                <div class="error">{{ $errors->first('password_confirmation') }}</div>--}}
{{--            @endif--}}
{{--        </label><br>--}}
{{--        @if($user_session['role_id'] == $role_ids['maintainer'])--}}
{{--        <hr>--}}
{{--        <label style="color: black; font-size: 20px;">What role does the user get?</label>--}}
{{--        <select name="roles" style="display: block; width: 100px;">--}}
{{--            @foreach($roles as $role)--}}
{{--                <option value="{{$role['id']}}" @if($role['id'] == $user['role_id'] || old('roles') == $role['id']) selected @endif>{{$role['name']}}</option>--}}
{{--            @endforeach--}}
{{--            @if($errors->has('roles'))--}}
{{--                <div class="error">{{ $errors->first('roles') }}</div>--}}
{{--            @endif--}}
{{--            @else--}}
{{--                <input type="hidden" name="roles" value="2">--}}
{{--            @endif--}}
{{--        </select><br>--}}
{{--        <input style="display: block;" type="submit" value="Save">--}}
{{--    </form>--}}
@endsection
