@extends('layouts.app')

@section('content')
    <h1>Gebruiker Informatie <a href="{{route('admin.users.index')}}" style="font-size: 30px;"><i class="fa-solid fa-backward-step"></i></a></h1>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="id">Gebruiker Id</label>
                <input type="text" class="form-control" id="id" value="@if(empty($user['id']))NULL @else{{$user['id']}} @endif" aria-describedby="id" placeholder="Id" disabled>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="name">Naam</label>
                <input type="text" class="form-control" id="name" value="@if(empty($user['name']))NULL @else{{$user['name']}} @endif" aria-describedby="name" placeholder="Naam" disabled>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="email">E-mail</label>
                <input type="email" class="form-control" id="email" value="@if(empty($user['email']))NULL @else{{$user['email']}} @endif" aria-describedby="email" placeholder="E-mail" disabled>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="password">Wachtwoord</label>
                <input type="password" class="form-control" id="password" value="@if(empty($user['password']))NULL @else{{$user['password']}} @endif" aria-describedby="password" placeholder="Wachtwoord" disabled>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="created_at">Gebruiker Gecreëerd</label>
                <input type="text" class="form-control" id="created_at" value="@if(empty($user['deleted_at']))NULL @else{{$user['deleted_at']}} @endif" aria-describedby="created_at" placeholder="Gebruiker Gecreëerd" disabled>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="updated_at">Laatst Bijgewerkt</label>
                <input type="text" class="form-control" id="updated_at" value="@if(empty($user['updated_at']))NULL @else{{$user['updated_at']}} @endif" aria-describedby="updated_at" placeholder="Laatst Bijgewerkt" disabled>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="updated_at">Gebruiker Verwijderd</label>
                <input type="text" class="form-control" id="updated_at" value="@if(empty($user['deleted_at']))NULL @else{{$user['deleted_at']}} @endif" aria-describedby="updated_at" placeholder="Gebruiker Verwijderd" disabled>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="updated_at">Rol Id</label>
                <input type="text" class="form-control" id="updated_at" value="@if(empty($user['role_id']))NULL @else{{$user['role_id']}} @endif" aria-describedby="updated_at" placeholder="Rol Id" disabled>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="updated_at">Rol</label>
                <input type="text" class="form-control" id="updated_at" value="@if(empty($user->role()->get()->first()->name))NULL @else{{$user->role()->get()->first()->name}} @endif" aria-describedby="updated_at" placeholder="Rol" disabled>
            </div>
        </div>
    </div>
@endsection
