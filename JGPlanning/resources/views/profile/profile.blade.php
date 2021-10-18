@extends('layouts.app')

@section('content')
    <h1>Uw Profiel</h1>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="name">Name</label>
                <input type="text" class="form-control" id="name" value="{{$user['name']}}" aria-describedby="name" placeholder="Name" disabled>
                @if($errors->has('name'))
                    <div class="error">{{ $errors->first('name') }}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="email">Email</label>
                <input type="email" class="form-control" id="email" value="{{$user['email']}}" aria-describedby="email" placeholder="Email" disabled>
                @if($errors->has('email'))
                    <div class="error">{{ $errors->first('email') }}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="role">Rol</label>
                <input type="text" class="form-control" id="role" value="{{$user['role']['name']}}" aria-describedby="role" placeholder="Rol" disabled>
            </div>
        </div>
    </div>
    <a href="{{route('profile.edit', $user['id'])}}" class="btn btn-primary" value="Bewerk">Bewerk</a>
@endsection
