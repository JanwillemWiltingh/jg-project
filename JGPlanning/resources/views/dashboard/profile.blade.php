@extends('layouts.app')

@section('content')
    <h1>Uw Profiel</h1>
        <h6>Name</h6>
        <label>
            <input style="width: 300px; height: 35px; font-size: 20px;" type="text" placeholder="Name" value="{{$user['name']}}" disabled>
        </label>
        <h6>Email</h6>
        <label>
            <input style="width: 300px; height: 35px; font-size: 20px;" type="text" placeholder="Email" value="{{$user['email']}}" disabled>
        </label>
        <h6>Rol</h6>
        <label>
            <input style="width: 300px; height: 35px; font-size: 20px;" type="text" placeholder="Email" value="{{$user['role']['name']}}" disabled>
        </label><br>
        <label><strong>Vraag een Admin om uw gegevens te veranderen</strong></label>
@endsection
