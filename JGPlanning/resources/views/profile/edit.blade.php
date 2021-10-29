@extends('layouts.app')

@section('content')
    @if(session()->get('message'))
        <div class="alert alert-{{ session()->get('message')['type'] }} alert-dismissible fade show" role="alert">
            {{ session()->get('message')['message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif
    <h1>Bewerk mijn Profiel</h1>
    <form method="get" action="{{route('profile.update', $user['id'])}}">
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="firstname">Voornaam</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" value="{{ old('firstname') ?? $user['firstname'] }}" aria-describedby="name" placeholder="Voornaam">
                    @if($errors->has('firstname'))
                        <div class="error">{{ $errors->first('firstname') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="middlename">Tussenvoegsel</label>
                    <input type="text" class="form-control" id="middlename" name="middlename" value="{{ old('middlename') ?? $user['middlename'] }}" aria-describedby="middlename" placeholder="Tussenvoegsel">
                    @if($errors->has('middlename'))
                        <div class="error">{{ $errors->first('middlename') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="lastname">Achternaam</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname') ?? $user['lastname'] }}" aria-describedby="lastname" placeholder="Achternaam">
                    @if($errors->has('lastname'))
                        <div class="error">{{ $errors->first('lastname') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') ?? $user['email'] }}" aria-describedby="email" placeholder="E-mail">

                    @if($errors->has('email'))
                        <div class="error">{{ $errors->first('email') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="password">Nieuw Wachtwoord</label>
                    <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}" aria-describedby="password" placeholder="Nieuw Wachtwoord">

                    @if($errors->has('password'))
                        <div class="error">{{ $errors->first('password') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="password_confirmation">Bevestig Nieuw Wachtwoord</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" aria-describedby="password_confirmation" placeholder="Voer wachtwoord opnieuw in">

                    @if($errors->has('password_confirmation'))
                        <div class="error">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="old_password">Oud Wachtwoord</label>
                    <input type="password" class="form-control" id="old_password" name="old_password" value="{{ old('old_password') }}" aria-describedby="old_password" placeholder="Voer je oude wachtwoord in">

                    @if($errors->has('old_password'))
                        <div class="error">{{ $errors->first('old_password') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" value="Save">Opslaan</button>
    </form>
@endsection

