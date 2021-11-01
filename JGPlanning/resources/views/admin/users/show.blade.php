@extends('layouts.app')

@section('content')
    <h1>Gebruiker Informatie <a href="{{route('admin.users.index')}}" style="font-size: 30px;"><i class="fa-solid fa-backward-step"></i></a></h1>
{{--  USER ID  --}}
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="id">Gebruiker Id</label>
                <input type="text" class="form-control" id="id" value="@if(empty($user['id']))NULL @else{{$user['id']}} @endif" aria-describedby="id" placeholder="Id" disabled>
            </div>
        </div>
{{--  USER FIRSTNAME  --}}
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="firstname">Voornaam</label>
                <input type="text" class="form-control" id="firstname" value="@if(empty($user['firstname']))NULL @else{{$user['firstname']}} @endif" aria-describedby="firstname" placeholder="Voornaam" disabled>
            </div>
        </div>
{{--  USER MIDDLENAME  --}}
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="middlename">Tussenvoegsel</label>
                <input type="text" class="form-control" id="middlename" value="@if(empty($user['middlename']))NULL @else{{$user['middlename']}} @endif" aria-describedby="middlename" placeholder="Tussenvoegsel" disabled>
            </div>
        </div>
{{--  USER LASTNAME  --}}
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="lastname">Achternaam</label>
                <input type="text" class="form-control" id="lastname" value="@if(empty($user['lastname']))NULL @else{{$user['lastname']}} @endif" aria-describedby="lastname" placeholder="Achternaam" disabled>
            </div>
        </div>
    </div>
{{--  USER EMAIL  --}}
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="email">E-mail</label>
                <input type="email" class="form-control" id="email" value="@if(empty($user['email']))NULL @else{{$user['email']}} @endif" aria-describedby="email" placeholder="E-mail" disabled>
            </div>
        </div>
    </div>
{{--  USER PASSWORD  --}}
{{--    <div class="row">--}}
{{--        <div class="col-3">--}}
{{--            <div class="form-group">--}}
{{--                <label class="black-label-text" for="password">Wachtwoord</label>--}}
{{--                <input type="password" class="form-control" id="password" value="@if(empty($user['password']))NULL @else{{$user['password']}} @endif" aria-describedby="password" placeholder="Wachtwoord" disabled>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--  USER CREATED  --}}
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="created_at">Gebruiker Gecreëerd</label>
                <input type="text" class="form-control" id="created_at" value="@if(empty($user['deleted_at'])) - @else{{$user['deleted_at']}} @endif" aria-describedby="created_at" placeholder="Gebruiker Gecreëerd" disabled>
            </div>
        </div>
{{--  USER LAST UPDATED  --}}
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="updated_at">Laatst Bijgewerkt</label>
                <input type="text" class="form-control" id="updated_at" value="@if(empty($user['updated_at'])) - @else{{$user['updated_at']}} @endif" aria-describedby="updated_at" placeholder="Laatst Bijgewerkt" disabled>
            </div>
        </div>
{{--  USER DELETED  --}}
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="updated_at">Gebruiker Verwijderd</label>
                <input type="text" class="form-control" id="updated_at" value="@if(empty($user['deleted_at'])) - @else{{$user['deleted_at']}} @endif" aria-describedby="updated_at" placeholder="Gebruiker Verwijderd" disabled>
            </div>
        </div>
    </div>
{{--  USER ROLE_ID  --}}
{{--    <div class="row">--}}
{{--        <div class="col-3">--}}
{{--            <div class="form-group">--}}
{{--                <label class="black-label-text" for="updated_at">Rol Id</label>--}}
{{--                <input type="text" class="form-control" id="updated_at" value="@if(empty($user['role_id']))NULL @else{{$user['role_id']}} @endif" aria-describedby="updated_at" placeholder="Rol Id" disabled>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--  USER ROLE  --}}
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="black-label-text" for="updated_at">Rol</label>
                <input type="text" class="form-control" id="updated_at" value="@if(empty($user->role()->get()->first()->name))NULL @else{{$user->role()->get()->first()->name}} @endif" aria-describedby="updated_at" placeholder="Rol" disabled>
            </div>
        </div>
    </div>
{{--  if they are maintainer they are allowed to edit anything  --}}
    @if($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))
        <a class="btn btn-primary" href ="{{route('admin.users.edit',$user['id'])}}"> Bewerk deze Gebruiker</a>
    @endif
{{--    if they are admin they can only edit employee--}}
    @if($user_session['role_id'] == App\Models\Role::getRoleID('admin') && $user['role_id'] == App\Models\Role::getRoleID('employee'))
        <a class="btn btn-primary" href ="{{route('admin.users.edit',$user['id'])}}"> Bewerk deze Gebruiker</a>
    @endif
@endsection
