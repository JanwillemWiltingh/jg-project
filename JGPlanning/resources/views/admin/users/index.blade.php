@extends('layouts.app')

@section('content')
    <h1>Alle Gebruikers <strong><a href="{{route('admin.users.create')}}"><i class="fa-solid fa-user-plus"></i></a></strong></h1>
{{--    <h5>--}}
{{--        <strong>--}}
{{--            <a class="btn btn-primary table-label-create" href="{{route('admin.users.create')}}">Create a new User</a>--}}
{{--        </strong>--}}
{{--    </h5>--}}
    <table class="table table-hover" style="box-shadow: 0 0 5px 0 lightgrey;">
        <thead>
            <tr>
                <th scope="col"><strong>#</strong></th>
                <th scope="col"><strong>@sortablelink('firstname', 'Voornaam')</strong></th>
                <th scope="col"><strong>Tussenvoegsel</strong></th>
                <th scope="col"><strong>@sortablelink('lastname', 'Achternaam')</strong></th>
                <th scope="col"><strong>@sortablelink('email', 'E-mail')</strong></th>
                <th scope="col"><strong>@sortablelink('role_id', 'Rol')</strong></th>
                <th scope="col"><strong>@sortablelink('deleted_at', 'Actief?')</strong></th>
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
                <td>
                    {{ $user['middlename'] ?? '' }}
                </td>
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
                        @if($user['role_id'] != App\Models\Role::getRoleID('maintainer'))
                            <strong>
                                <a class="table-label" href="{{route('admin.users.edit',$user['id'])}}"><i class="fa-solid fa-user-pen"></i></a>
                            </strong>
                        @else
                            <i class="fa-solid fa-user-lock"></i>
                        @endif
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
                            @elseif($user_session['role_id'] == App\Models\Role::getRoleID('maintainer') && $user['role_id'] != App\Models\Role::getRoleID('maintainer'))
                                @if(empty($user['deleted_at']))
                                    <a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}"><i class="fa-solid fa-user-slash"></i></a>
                                @else
                                    <a class="table-label-green" href="{{route('admin.users.destroy',$user['id'])}}"><i class="fa-solid fa-user-check"></i><a/>
                                @endif
                            @else
                                <i class="fa-solid fa-user-lock"></i>
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
