@extends('layouts.app')

@section('content')
    @if($user_session['role_id'] == 3)
        <h1>All Users</h1>
        {{--Fancy points; if you are an admin, you will see employee. Maintainer sees normal user--}}
        <h5><strong><a class="table-label" href="{{route('admin.users.create')}}">Create a new User</a></strong></h5>
        @if(session()->get('message')) {{ session()->get('message') }} @endif
        <table class="table">
            <thead>
            <tr>
                <th scope="col"><strong>#</strong></th>
                <th scope="col"><strong>Name</strong></th>
                <th scope="col"><strong>Email</strong></th>
                <th scope="col"><strong>Role_id</strong></th>
                <th scope="col"><strong>Role</strong></th>
                <th scope="col"><strong>Active</strong></th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            {{--Loop each user to show in a table--}}
            @foreach($users as $user)
                <tr>
                    <td>{{$user['id']}}</td>
                    {{--Check the email from the current user and the email in the database to show who is selected(logged in)--}}
                    @if($user['email'] == $user_session['email'])
                        <td><strong>{{$user['name']}}</strong></td>
                    @else
                        <td>{{$user['name']}}</td>
                    @endif

                    <td>{{$user['email']}}</td>
                    <td>{{$user['role_id']}}</td>

                    {{--Big letter maintainer--}}
                    <td>@if($user->role()->get()->first()->name == 'Maintainer')<strong>{{$user->role()->get()->first()->name}}</strong> @else {{$user->role()->get()->first()->name}} @endif</td>

                    {{--Shows if the user is soft-deleted(active) or not--}}
                    @if(empty($user['deleted_at']))
                        <td >Yes</td>
                    @else
                        <td>No</td>
                    @endif
                    <td><strong><a class="table-label" href="{{route('admin.users.edit',$user['id'])}}">Bewerk</a></strong></td>
                    <td><strong><a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}">@if($user['role_id'] != 3)@if(empty($user['deleted_at']))Zet naar Inactief @else Zet naar Actief @endif @endif</a></strong></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <h1>All Users</h1>
        <h5><a class="table-label" href="{{route('admin.users.create')}}">Create a new Employee</a></h5>
        @if(session()->get('message')) {{ session()->get('message') }} @endif
        <table class="table">
            <thead>
            <tr>
                <th scope="col"><strong>#</strong></th>
                <th scope="col"><strong>Name</strong></th>
                <th scope="col"><strong>Email</strong></th>
                <th scope="col"><strong>Role_id</strong></th>
                <th scope="col"><strong>Role</strong></th>
                <th scope="col"><strong>Active</strong></th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            {{--Loop each user to show in a table--}}
            @foreach($users as $user)
                <tr>
                    <td>{{$user['id']}}</td>
                    {{--Check the email from the current user and the email in the database to show who is selected(logged in)--}}
                    @if($user['email'] == $user_session['email'])
                        <td><strong>{{$user['name']}}</strong></td>
                    @else
                        <td>{{$user['name']}}</td>
                    @endif

                    <td>{{$user['email']}}</td>
                    <td>{{$user['role_id']}}</td>

                    {{--Big letter maintainer--}}
                    <td>@if($user->role()->get()->first()->name == 'Maintainer')<strong>{{$user->role()->get()->first()->name}}</strong> @else {{$user->role()->get()->first()->name}} @endif</td>

                    {{--Shows if the user is soft-deleted(active) or not--}}
                    @if(empty($user['deleted_at']))
                        <td >Yes</td>
                    @else
                        <td>No</td>
                    @endif
                    {{--Admin's and Maintainers can't be deleted nor be edited--}}
                    @if($user['role_id'] == 2)
                        <td><a class="table-label" href="{{route('admin.users.edit',$user['id'])}}">Bewerk</a></td>
                    @else <td><i>Kan alleen Employee's bewerken</i></td>
                    @endif
                    @if($user['role_id'] == 1 || $user['role_id'] == 3)
                        <td><i>Kan geen admin verwijderen</i></td>
                    @else
                        <td><a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}">@if(empty($user['deleted_at']))Zet naar Inactief @else Zet naar Actief @endif</a></td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection
