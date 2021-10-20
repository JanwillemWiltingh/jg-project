@extends('layouts.app')

@section('content')
    @if(session()->get('message'))
        <div class="alert alert-{{ session()->get('message')['type'] }} alert-dismissible fade show" role="alert">
            {{ session()->get('message')['message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif
    <h1>Maak een nieuwe Gebruiker aan <a href="{{route('admin.users.index')}}" style="font-size: 30px;"><i class="fa-solid fa-backward-step"></i></a></h1>
    <form method="get" action="{{ route('admin.users.store') }}">
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="name">Naam</label>
                    <input type="text" class="form-control" id="name" name="name" aria-describedby="name" placeholder="Naam">

                    @if($errors->has('name'))
                        <div class="error">{{ $errors->first('name') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="email" placeholder="E-mail">

                    @if($errors->has('email'))
                        <div class="error">{{ $errors->first('email') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="password">Wachtwoord</label>
                    <input type="password" class="form-control" id="password" name="password" aria-describedby="password" placeholder="Wachtwoord">

                    @if($errors->has('password'))
                        <div class="error">{{ $errors->first('password') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="password_confirmation">Bevestig Wachtwoord</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" aria-describedby="password_confirmation" placeholder="Bevestig Wachtwoord">

                    @if($errors->has('password_confirmation'))
                        <div class="error">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>
            </div>
        </div>
        @if($user_session['role_id'] == $role_ids['maintainer'])
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
                            <div class="error">{{ $errors->first('roles') }}</div>
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
