@extends('layouts.app')

@section('content')
    <h1>Alle Gebruikers</h1>

    <h5>
        <strong>
            <a class="table-label" href="{{route('admin.users.create')}}">Create a new User</a>
        </strong>
    </h5>
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
            <tr class="{{ $user->isCurrentUser() }}">
                <th scope="row">{{ $loop->index }}</th>
                {{--Check the email from the current user and the email in the database to show who is selected(logged in)--}}
                <td>
                    @if($user['id'] == $user_session['id'])
                        <strong>{{$user['name']}}</strong>
                    @else
                        {{$user['name']}}
                    @endif
                </td>

                <td>{{$user['email']}}</td>
                <td>{{$user['role_id']}}</td>

                {{--Big letter maintainer--}}
                <td>@if($user->hasRole('maintainer'))<strong>{{$user->role()->get()->first()->name}}</strong> @else {{$user->role()->get()->first()->name}} @endif</td>

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
                    @if($user_session['role_id'] == $roles['admin'])
                        @if($user['role_id'] != $roles['employee'])
                            <i>Kan alleen Employee's bewerken</i>
                        @else
                            <strong>
                                <a class="table-label" href="{{route('admin.users.edit',$user['id'])}}">Bewerk</a>
                            </strong>
                        @endif
                    @elseif($user_session['role_id'] == $roles['maintainer'])
                        <strong>
                            <a class="table-label" href="{{route('admin.users.edit',$user['id'])}}">Bewerk</a>
                        </strong>
                    @endif
                </td>

                {{-- Check if the user is allowed to delete the user --}}
                <td>
                    @if($user_session['role_id'] == $roles['admin'])
                        @if($user['role_id'] != $roles['employee'])
                            <i>Kan alleen employee's verwijderen</i>
                        @else
                            <strong>
                                <a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}">
                                    @if($user['role_id'] != 1)
                                        @if(empty($user['deleted_at']))
                                            Zet naar Inactief
                                        @else
                                            Zet naar Actief
                                        @endif
                                    @endif
                                </a>
                            </strong>
                        @endif
                    @elseif($user_session['role_id'] == $roles['maintainer'])
                        <strong>
                            <a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}">
                                @if($user['role_id'] != 1)
                                    @if(empty($user['deleted_at']))
                                        Zet naar Inactief
                                    @else
                                        Zet naar Actief
                                    @endif
                                @endif
                            </a>
                        </strong>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
