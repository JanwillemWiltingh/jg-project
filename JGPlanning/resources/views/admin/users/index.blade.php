@extends('layouts.app')

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Role_id</th>
            <th scope="col">Role</th>
            <th scope="col">Active</th>
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
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
