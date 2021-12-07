@extends('layouts.app')

@section('content')
    <div class="crud-user-form fadeInDown">
        <h1>Gebruiker Informatie</h1>
        <div class="card">
            <div class="card-body">
            {{--  USER ID  --}}
                    <div class="row">
                        <div class="form-group">
                            <label class="black-label-text" for="id">Gebruiker Id</label>
                            <input type="text" class="form-control" id="id" value="@if(empty($user['id']))NULL @else{{$user['id']}} @endif" aria-describedby="id" placeholder="Id" disabled>
                        </div>
                    </div>
            {{--  USER FIRSTNAME  --}}
                    <div class="row">
                        <div class="form-group">
                            <label class="black-label-text" for="firstname">Voornaam</label>
                            <input type="text" class="form-control" id="firstname" value="@if(empty($user['firstname']))NULL @else{{$user['firstname']}} @endif" aria-describedby="firstname" placeholder="Voornaam" disabled>
                        </div>
                    </div>
            {{--  USER MIDDLENAME  --}}
                    <div class="row">
                        <div class="form-group">
                            <label class="black-label-text" for="middlename">Tussenvoegsel</label>
                            <input type="text" class="form-control" id="middlename" value="@if(empty($user['middlename']))NULL @else{{$user['middlename']}} @endif" aria-describedby="middlename" placeholder="Tussenvoegsel" disabled>
                        </div>
                    </div>
            {{--  USER LASTNAME  --}}
                    <div class="row">
                        <div class="form-group">
                            <label class="black-label-text" for="lastname">Achternaam</label>
                            <input type="text" class="form-control" id="lastname" value="@if(empty($user['lastname']))NULL @else{{$user['lastname']}} @endif" aria-describedby="lastname" placeholder="Achternaam" disabled>
                        </div>
                    </div>

            {{--  USER EMAIL  --}}
                    <div class="row">
                        <div class="form-group">
                            <label class="black-label-text" for="email">E-mail</label>
                            <input type="email" class="form-control" id="email" value="@if(empty($user['email']))NULL @else{{$user['email']}} @endif" aria-describedby="email" placeholder="E-mail" disabled>
                        </div>
                    </div>
                <div class="row">
                    <div class="form-group">
                        <label class="black-label-text" for="email">Telefoonnummer</label>
                        <input type="email" class="form-control" id="email" value="@if(empty($user['phone_number']))NULL @else{{$user['phone_number']}} @endif" aria-describedby="email" placeholder="E-mail" disabled>
                    </div>
                </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="black-label-text" for="created_at">Gebruiker Gecreëerd</label>
                            <input type="text" class="form-control" id="created_at" value="@if(empty($user['created_at'])) - @else{{$user['created_at']}} @endif" aria-describedby="created_at" placeholder="Gebruiker Gecreëerd" disabled>
                        </div>
                    </div>
            {{--  USER LAST UPDATED  --}}
                    <div class="row">
                        <div class="form-group">
                            <label class="black-label-text" for="updated_at">Laatst Bijgewerkt</label>
                            <input type="text" class="form-control" id="updated_at" value="@if(empty($user['updated_at'])) - @else{{$user['updated_at']}} @endif" aria-describedby="updated_at" placeholder="Laatst Bijgewerkt" disabled>
                        </div>
                    </div>
            {{--  USER DELETED  --}}
                    <div class="row">
                        <div class="form-group">
                            <label class="black-label-text" for="updated_at">Gebruiker Verwijderd</label>
                            <input type="text" class="form-control" id="updated_at" value="@if(empty($user['deleted_at'])) - @else{{$user['deleted_at']}} @endif" aria-describedby="updated_at" placeholder="Gebruiker Verwijderd" disabled>
                        </div>
                    </div>
            {{--  USER ROLE  --}}
                    <div class="row">
                        <div class="form-group">
                            <label class="black-label-text" for="updated_at">Rol</label>
                            <input type="text" class="form-control" id="updated_at" value="@if(empty($user->role()->get()->first()->name))NULL @else{{$user->role()->get()->first()->name}} @endif" aria-describedby="updated_at" placeholder="Rol" disabled>
                        </div>
                    </div>
            {{--  if they are maintainer they are allowed to edit anything  --}}
                @if($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))
                    <a style="float: right" class="btn btn-primary jg-color-3 border-0" href ="{{route('admin.users.edit',$user['id'])}}"> Bewerk deze Gebruiker</a>
                @endif
            {{--    if they are admin they can only edit employee--}}
                @if($user_session['role_id'] == App\Models\Role::getRoleID('admin') && $user['role_id'] == App\Models\Role::getRoleID('employee'))
                    <a style="float: right" class="btn btn-primary jg-color-3 border-0" href ="{{route('admin.users.edit',$user['id'])}}"> Bewerk deze Gebruiker</a>
                @endif
                <button style="float: right" class="btn btn-primary jg-color-3 border-0" value="Ga Terug"><a href="{{route('admin.users.index')}}" style="text-decoration: none; color: white;">Ga Terug</a></button>
            </div>
        </div>
    </div>
@endsection
