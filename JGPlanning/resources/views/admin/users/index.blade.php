@extends('layouts.app')

@section('content')
    <table style="">
        @foreach($users as $user)
        <tr style="border: 1px solid darkgray; padding: 4px; text-align: center;">
            <th style="border: 1px solid darkgray; padding: 4px; text-align: center;">Id</th>
            <th style="border: 1px solid darkgray; padding: 4px; text-align: center;">Name</th>
            <th style="border: 1px solid darkgray; padding: 4px; text-align: center;">Email</th>
            <th style="border: 1px solid darkgray; padding: 4px; text-align: center;">Role_id</th>
            <th style="border: 1px solid darkgray; padding: 4px; text-align: center;">Role</th>
            <th style="border: 1px solid darkgray; padding: 4px; text-align: center;">Active</th>
        </tr>
        <tr style="border: 1px solid darkgray; padding: 4px; text-align: center;">
            <td style="border: 1px solid darkgray; padding: 4px; text-align: center;">{{$user['id']}}</td>
            <td style="border: 1px solid darkgray; padding: 4px; text-align: center;">{{$user['name']}}</td>
            <td style="border: 1px solid darkgray; padding: 4px; text-align: center;">{{$user['email']}}</td>
            <td style="border: 1px solid darkgray; padding: 4px; text-align: center;">{{$user['role_id']}}</td>
        @if($user['role_id'] == 1)
                <td style="border: 1px solid darkgray; padding: 4px; text-align: center;">Admin</td>
            @else
                <td style="border: 1px solid darkgray; padding: 4px; text-align: center;">User</td>
            @endif
        @if(empty($user['deleted_at']))
            <td style="border: 1px solid darkgray; padding: 4px; text-align: center;">Yes</td>
            @else
            <td style="border: 1px solid darkgray; padding: 4px; text-align: center;">No</td>
            @endif
        </tr>
        @endforeach
    </table>
@endsection
