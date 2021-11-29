@extends('layouts.app')

@section('content')
<div class="fadeInDown crud-table">

    <h1>Alle Gebruikers <strong><a href="{{route('admin.users.create')}}" data-toggle="tooltip" title="Gebruiker Toevoegen"><i class="fa-solid fa-user-plus icon-color"></i></a></strong></h1>
    <input type="text" id="search" class="form-control" placeholder="Zoek..." style="width: 25%">
    <br>
    <table class="table table-hover" id="user_crud">
        <thead>
        <tr>
            <th scope="col"><strong>#</strong></th>
            <th scope="col"><strong>Voornaam</strong></th>
            <th scope="col"><strong>Tussenvoegsel</strong></th>
            <th scope="col"><strong>Achternaam</strong></th>
            <th scope="col"><strong>E-mail</strong></th>
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
                <th scope="row">{{ $loop->index + 1 }}</th>
                {{--Check the email from the current user and the email in the database to show who is selected(logged in)--}}
                <td>{{$user['firstname']}}</td>
                <td>{{ $user['middlename'] ?? '' }}</td>
                <td>{{$user['lastname']}}</td>

                <td>{{$user['email']}}</td>

                {{--Big letter maintainer--}}
                <td>@if($user['role_id'] == App\Models\Role::getRoleID('maintainer'))<strong>{{__('general.' .$user->role()->get()->first()->name)}}</strong> @else {{__('general.' .$user->role()->get()->first()->name)}} @endif</td>

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
                                <a class="table-label" href="{{route('admin.users.edit',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Aanpassen"><i class="fa-solid fa-user-pen icon-color"></i></a>
                            </strong>
                        @endif
                    @elseif($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))
                        @if($user['role_id'] != App\Models\Role::getRoleID('maintainer'))
                            <strong>
                                <a class="table-label" href="{{route('admin.users.edit',$user['id'])}}" data-toggle="tooltip" title="Gebruiker aanpassen"><i class="fa-solid fa-user-pen icon-color"></i></a>
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
                                            <a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Verwijderen"><i class="fa-solid fa-user-slash"></i></a>
                                        @else
                                            <a class="table-label-green" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Herstellen"><i class="fa-solid fa-user-check"></i></a>
                                        @endif
                                    @endif
                                </a>
                            </strong>
                        @endif
                    @elseif($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))
                        <strong>
                            @if($user['role_id'] != App\Models\Role::getRoleID('maintainer'))
                                @if(empty($user['deleted_at']))
                                    <a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Verwijderen"><i class="fa-solid fa-user-slash"></i></a>
                                @else
                                    <a class="table-label-green" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Herstellen"><i class="fa-solid fa-user-check"></i><a/>
                                @endif
                            @elseif($user_session['role_id'] == App\Models\Role::getRoleID('maintainer') && $user['role_id'] != App\Models\Role::getRoleID('maintainer'))
                                @if(empty($user['deleted_at']))
                                    <a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Verwijderen"><i class="fa-solid fa-user-slash"></i></a>
                                @else
                                    <a class="table-label-green" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Herstellen"><i class="fa-solid fa-user-check"></i><a/>
                                @endif
                            @else
                                <i class="fa-solid fa-user-lock"></i>
                            @endif
                        </strong>
                    @endif
                </td>
                <td>
                    <a class="table-label" href="{{route('admin.users.show',$user['id'])}}" data-toggle="tooltip" title="Bekijken"><i class="fa-solid fa-user-gear icon-color"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
