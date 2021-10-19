@extends('layouts.app')

@section('content')
    @if(session()->get('message'))
        <div class="alert alert-{{ session()->get('message')['type'] }} alert-dismissible fade show" role="alert">
            {{ session()->get('message')['message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif
    <h1>Alle Gebruikers <strong><a href="{{route('admin.users.create')}}"><i class="fa-solid fa-user-plus"></i></a></strong></h1>
{{--    <h5>--}}
{{--        <strong>--}}
{{--            <a class="btn btn-primary table-label-create" href="{{route('admin.users.create')}}">Create a new User</a>--}}
{{--        </strong>--}}
{{--    </h5>--}}
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
                <td>@if($user->hasRole('maintainer'))<strong>{{ucfirst($user->role()->get()->first()->name)}}</strong> @else {{ucfirst($user->role()->get()->first()->name)}} @endif</td>

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
                                <a class="table-label" href="{{route('admin.users.edit',$user['id'])}}"><i class="fa-solid fa-user-pen"></i></a>
                            </strong>
                        @endif
                    @elseif($user_session['role_id'] == $roles['maintainer'])
                        <strong>
                            <a class="table-label" href="{{route('admin.users.edit',$user['id'])}}"><i class="fa-solid fa-user-pen"></i></a>
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
                                            <a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}"><i class="fa-solid fa-user-slash"></i></a>
                                        @else
                                            <a class="table-label-green" href="{{route('admin.users.destroy',$user['id'])}}"><i class="fa-solid fa-user-check"></i><a/>
                                        @endif
                                    @endif
                                </a>
                            </strong>
                        @endif
                    @elseif($user_session['role_id'] == $roles['maintainer'])
                        <strong>
                            @if($user['role_id'] != 1)
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
@endsection
