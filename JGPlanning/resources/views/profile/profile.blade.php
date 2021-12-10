@extends('layouts.app')

@section('content')
    <div class="crud-user-form fadeInDown">
    <h1>Uw Profiel</h1>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="@if(!empty($user['middlename']))col-4 @else col-6 @endif">
                    <div class="form-group">
                        <label class="black-label-text" for="name">Voornaam</label>
                        <input type="text" class="form-control" id="name" value="{{$user['firstname']}}" aria-describedby="name" placeholder="Voornaam" readonly>
                    </div>
                </div>
            @if(!empty($user['middlename']))
                <div class="col-4">
                    <div class="form-group">
                        <label class="black-label-text" for="name">Middlename</label>
                        <input type="text" class="form-control" id="name" value="{{$user['middlename']}}" aria-describedby="name" placeholder="Voornaam" readonly>
                    </div>
                </div>
            @endif
                <div class="@if(!empty($user['middlename']))col-4 @else col-6 @endif">
                    <div class="form-group">
                        <label class="black-label-text" for="name">Achternaam</label>
                        <input type="text" class="form-control" id="name" value="{{$user['lastname']}}" aria-describedby="name" placeholder="Achternaam" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label class="black-label-text" for="email">Email</label>
                    <input type="email" class="form-control" id="email" value="{{$user['email']}}" aria-describedby="email" placeholder="Email" readonly>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label class="black-label-text" for="phone_number">Telefoonnummer</label>
                    <input type="text" class="form-control" id="phone_number" value="{{$user['phone_number']}}" aria-describedby="phone_number" placeholder="Telefoonnummer" readonly>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="form-group">
                    <label class="black-label-text" for="roles">Rol</label>
                    <input type="text" class="form-control" id="name" value="{{__('general.' .$user['role']['name'])}}" aria-describedby="name" placeholder="Achternaam" readonly>
                </div>
            </div>

            <button class="btn btn-primary jg-color-3 border-0" value="Ga Terug"><a href="{{route('dashboard.home')}}" style="text-decoration: none; color: white;">Ga Terug</a></button>
            <a href="{{route('profile.edit', $user['id'])}}" style="float: right" class="btn btn-primary jg-color-3 border-0">Bewerk</a>
            <a id="changeFont"  href="#" >.</a>
        </div>
    </div>
@endsection
