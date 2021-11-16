@extends('layouts.app')

@section('content')
    <h1>Uw Profiel</h1>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="name">Voornaam</label>
                <input type="text" class="form-control" id="name" value="{{$user['firstname']}}" aria-describedby="name" placeholder="Voornaam" readonly>
                @if($errors->has('name'))
                    <div class="error">{{ $errors->first('name') }}</div>
                @endif
            </div>
        </div>

    @if(!empty($user['middlename']))
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="name">Middlename</label>
                <input type="text" class="form-control" id="name" value="{{$user['middlename']}}" aria-describedby="name" placeholder="Voornaam" readonly>
                @if($errors->has('name'))
                    <div class="error">{{ $errors->first('name') }}</div>
                @endif
            </div>
        </div>
    @endif

        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="name">Achternaam</label>
                <input type="text" class="form-control" id="name" value="{{$user['lastname']}}" aria-describedby="name" placeholder="Achternaam" readonly>
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
                <input type="email" class="form-control" id="email" value="{{$user['email']}}" aria-describedby="email" placeholder="Email" readonly>
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
                <input type="text" class="form-control" id="role" value="{{$user['role']['name']}}" aria-describedby="role" placeholder="Rol" readonly>
            </div>
        </div>
    </div>
    <a href="{{route('profile.edit', $user['id'])}}" class="btn btn-primary" value="Bewerk">Bewerk</a>
    <a id="changeFont"  href="#" >.</a>
@endsection
