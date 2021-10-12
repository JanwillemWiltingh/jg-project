@extends('layouts.app')

@section('content')
    <h1>All Users</h1>
    <h5><a href="{{route('admin.users.create')}}">Create a new user</a></h5>
    @if(session()->get('message')) {{ session()->get('message') }} @endif
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Role_id</th>
            <th scope="col">Role</th>
            <th scope="col">Active</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>

        @foreach($users as $user)
            <tr>
                <td>{{$user['id']}}</td>
                <td>{{$user['name']}}</td>
                <td>{{$user['email']}}</td>
                <td>{{$user['role_id']}}</td>
                <td>{{$user->role()->get()->first()->name}}</td>
                @if(empty($user['deleted_at']))
                    <td >Yes</td>
                @else
                    <td>No</td>
                @endif
                <td><a href="{{route('admin.users.edit',$user['id'])}}">Edit</a></td>
                <td><a href="{{route('admin.users.destroy',$user['id'])}}">@if(empty($user['deleted_at']))Delete @else Un-Delete @endif</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
