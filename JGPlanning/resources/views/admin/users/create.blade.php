@extends('layouts.app')

@section('content')
<div class="crud-user-form fadeInDown">
    <text class="crud-user-form-title">Maak een nieuwe Gebruiker aan </text>
    <div class="card">
        <div class="card-body">
            <form method="get" action="{{ route('admin.users.store') }}">
                <div class="row">
{{--                    <div class="col-3">--}}
                        <x-forms.input type="text" name="firstname"></x-forms.input>
{{--                    </div>--}}
                </div>

                <div class="row">
{{--                    <div class="col-3">--}}
                        <x-forms.input type="text" name="middlename"></x-forms.input>
{{--                    </div>--}}
                </div>

            <div class="col-3">
                <x-forms.input type="text" name="lastname"></x-forms.input>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <x-forms.input type="email" name="email"></x-forms.input>
            </div>
        </div>

        @if($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))
            <hr>
            <label class="black-label-text" style="font-size: 20px;">Welke rol krijgt de gebruiker?</label>
            <div class="row">
                <div class="col-3">
                    <x-forms.single-select :array="$roles" :fields="['name']" field="name" name="roles" value="{{ $user_session['role_id'] }}" capitalize="true"></x-forms.single-select>
                </div>
            </div>
        @else
            <input type="hidden" name="roles" value="2">
        @endif

        <button type="submit" class="btn btn-primary" value="Save">Creëer</button>
    </form>
@endsection
