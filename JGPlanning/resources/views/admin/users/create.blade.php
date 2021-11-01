@extends('layouts.app')

@section('content')
    <h1>Maak een nieuwe Gebruiker aan <a href="{{route('admin.users.index')}}" style="font-size: 30px;"><i class="fa-solid fa-backward-step"></i></a></h1>
    <form method="get" action="{{ route('admin.users.store') }}">
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="firstname">Voornaam</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" value="{{ old('firstname') }}" aria-describedby="firstname" placeholder="Voornaam">

                    @if($errors->has('firstname'))firstname
                        <div class="error">
                            <label class="warning-label">
                                {{ $errors->first('firstname') }}
                            </label>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="middlename">Tussenvoegsel</label>
                    <input type="text" class="form-control" id="middlename" name="middlename" value="{{ old('middlename') }}" aria-describedby="middlename" placeholder="Tussenvoegsel">

                    @if($errors->has('middlename'))
                        <div class="error">
                            <label class="warning-label">
                                {{ $errors->first('middlename') }}
                            </label>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="lastname">Achternaam</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname') }}" aria-describedby="lastname" placeholder="Achternaam">

                    @if($errors->has('lastname'))
                        <div class="error">
                            <label class="warning-label">
                                {{ $errors->first('lastname') }}
                            </label>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" aria-describedby="email" placeholder="E-mail">

                    @if($errors->has('email'))
                        <div class="error">
                            <label class="warning-label">
                                {{ $errors->first('email') }}
                            </label>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="password">Wachtwoord</label>
                    <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}" aria-describedby="password" placeholder="Wachtwoord">

                    @if($errors->has('password'))
                        <div class="error">
                            <label class="warning-label">
                                {{ $errors->first('password') }}
                            </label>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="password_confirmation">Bevestig Wachtwoord</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" aria-describedby="password_confirmation" placeholder="Bevestig Wachtwoord">

                    @if($errors->has('password_confirmation'))
                        <div class="error">
                            <label class="warning-label">
                                {{ $errors->first('password_confirmation') }}
                            </label>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))
            <hr>
            <label class="black-label-text" style="font-size: 20px;">Welke rol krijgt de gebruiker?</label>
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label class="black-label-text" for="roles">Rollen</label>
                        <select class="form-control" name="roles" id="roles">
                            @foreach($roles as $role)
                                <option value="{{$role['id']}}" @if(old('roles') == $role['id']) selected @endif>{{$role['name']}}</option>
                            @endforeach
                        </select>

                        @if($errors->has('roles'))
                            <div class="error">
                                <label class="warning-label">
                                    {{ $errors->first('roles') }}
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <input type="hidden" name="roles" value="2">
        @endif

        <button type="submit" class="btn btn-primary" value="Save">Creëer</button>
    </form>
@endsection
