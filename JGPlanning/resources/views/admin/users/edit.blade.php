@extends('layouts.app')

@section('content')
    <h1>Bewerk Gebruiker <a href="{{route('admin.users.index')}}" style="font-size: 30px;"><i class="fa-solid fa-backward-step"></i></a></h1>
    <form method="get" action="{{ route('admin.users.update', $user['id']) }}">
        <div class="row">
            <div class="col-3">
                <x-forms.input type="text" name="firstname" value="{{ $user['firstname'] }}"></x-forms.input>
            </div>

            <div class="col-3">
                <x-forms.input type="text" name="middlename" value="{{ $user['middlename'] }}"></x-forms.input>
            </div>

            <div class="col-3">
                <x-forms.input type="text" name="lastname" value="{{ $user['lastname'] }}"></x-forms.input>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <x-forms.input type="email" name="email" value="{{ $user['email'] }}"></x-forms.input>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <x-forms.input type="password" name="password"></x-forms.input>
            </div>

            <div class="col-3">
                <x-forms.input type="password" name="password_confirmation"></x-forms.input>
            </div>
        </div>
        @if($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))
            <hr>
            <label class="black-label-text" style="font-size: 20px;">Welke rol krijgt de gebruiker?</label>
            <div class="row">
                <div class="col-3">
                    <x-forms.single-select :array="$roles" field="name" name="roles" value="{{ $user['role_id'] }}" capitalize="true"></x-forms.single-select>
                </div>
            </div>
        @else
            <input type="hidden" name="roles" value="2">
        @endif

        <button type="submit" class="btn btn-primary" value="Opslaan">Opslaan</button>
    </form>
@endsection
