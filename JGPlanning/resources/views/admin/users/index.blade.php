@extends('layouts.app')

@section('content')
<div class="fadeInDown crud-table">

    <h1>Alle Gebruikers <strong><a href="{{route('admin.users.create')}}"><i class="fa-solid fa-user-plus"></i></a></strong></h1>
    <input type="text" id="search" class="form-control" placeholder="Zoek..." style="width: 25%">
    <br>
    {{--    <h5>--}}
    {{--        <strong>--}}
    {{--            <a class="btn btn-primary table-label-create" href="{{route('admin.users.create')}}">Create a new User</a>--}}
    {{--        </strong>--}}
    {{--    </h5>--}}
    <table class="table table-hover" id="user_crud">
        <thead>
        <tr>
            <th scope="col"><strong>#</strong></th>
            <th scope="col"><strong>Voornaam</strong></th>
            <th scope="col"><strong>Tussenvoegsel</strong></th>
            <th scope="col"><strong>Achternaam</strong></th>
            <th scope="col"><strong>E-mail</strong></th>
            <th scope="col"><strong>Rol ID</strong></th>
            <th scope="col"><strong>Rol</strong></th>
            <th scope="col"><strong>Actief?</strong></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        {{--Loop each user to show in a table--}}
        @foreach($users as $user)
            <tr class="{{ $user->isCurrentUser() }}">
                <th scope="row">{{ $loop->index }}</th>
                {{--Check the email from the current user and the email in the database to show who is selected(logged in)--}}
                <td>
                    {{$user['firstname']}}
                </td>
                @if(empty($user['middlename']))
                    <td>
                        <i>NULL</i>
                    </td>
                @else
                    <td>
                        {{$user['middlename']}}
                    </td>
                @endif
                <td>
                    {{$user['lastname']}}
                </td>

                <td>{{$user['email']}}</td>

                {{--Big letter maintainer--}}
                <td>@if($user['role_id'] == App\Models\Role::getRoleID('maintainer'))<strong>{{ucfirst($user->role()->get()->first()->name)}}</strong> @else {{ucfirst($user->role()->get()->first()->name)}} @endif</td>

                {{--Shows if the user is soft-deleted(active) or not--}}
                <td>
                    @if(empty($user['deleted_at']))
                        JA
                    @else
                        NEE
                    @endif
                </td>

                {{-- Check if the user is allowed to edit the user --}}
                <td>
                    @if($user_session['role_id'] == App\Models\Role::getRoleID('admin'))
                        @if($user['role_id'] != App\Models\Role::getRoleID('employee'))
                            <i class="fa-solid fa-user-lock"></i>
                        @else
                            <strong>
                                <a class="table-label" href="{{route('admin.users.edit',$user['id'])}}"><i class="fa-solid fa-user-pen"></i></a>
                            </strong>
                        @endif
                    @elseif($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))
                        <strong>
                            <a class="table-label" href="{{route('admin.users.edit',$user['id'])}}"><i class="fa-solid fa-user-pen"></i></a>
                        </strong>
                    @endif
                </td>

                {{-- Check if the user is allowed to delete the user --}}
                <td>
                    @if($user_session['role_id'] == App\Models\Role::getRoleID('admin'))
                        @if($user['role_id'] != App\Models\Role::getRoleID('employee'))
                            <i class="fa-solid fa-user-lock"></i>
                        @else
                            <strong>
                                <a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}">
                                    @if($user['role_id'] != App\Models\Role::getRoleID('maintainer'))
                                        @if(empty($user['deleted_at']))
                                            <a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}"><i class="fa-solid fa-user-slash"></i></a>
                                        @else
                                            <a class="table-label-green" href="{{route('admin.users.destroy',$user['id'])}}"><i class="fa-solid fa-user-check"></i></a>
                                        @endif
                                    @endif
                                </a>
                            </strong>
                        @endif
                    @elseif($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))
                        <strong>
                            @if($user['role_id'] != App\Models\Role::getRoleID('maintainer'))
                                @if(empty($user['deleted_at']))
                                    <a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}"><i class="fa-solid fa-user-slash"></i></a>
                                @else
                                    <a class="table-label-green" href="{{route('admin.users.destroy',$user['id'])}}"><i class="fa-solid fa-user-check"></i><a/>
                                @endif
                            @elseif($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))
                                @if(empty($user['deleted_at']))
                                    <a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}"><i class="fa-solid fa-user-slash"></i></a>
                                @else
                                    <a class="table-label-green" href="{{route('admin.users.destroy',$user['id'])}}"><i class="fa-solid fa-user-check"></i><a/>
                                @endif
                            @endif
                        </strong>
                    @endif
                </td>
                <td>
                    <a class="table-label" href="{{route('admin.users.show',$user['id'])}}"><i class="fa-solid fa-user-gear"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! $users->appends(Request::except('page'))->render() !!}

    <p>
        Laat {{$users->count()}} van de {{ $users->total() }} gebruiker(s) zien.
    </p>
@endsection
