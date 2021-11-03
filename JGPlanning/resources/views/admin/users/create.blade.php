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

                <div class="row">
{{--                    <div class="col-3">--}}
                        <x-forms.input type="text" name="lastname"></x-forms.input>
{{--                    </div>--}}
                </div>
                <div class="row">
{{--                    <div class="col-3">--}}
                        <x-forms.input type="email" name="email"></x-forms.input>
{{--                    </div>--}}
                </div>
                <div class="row">
{{--                    <div class="col-3">--}}
                        <x-forms.input type="password" name="password"></x-forms.input>
{{--                    </div>--}}
                </div>

                <div class="row">
{{--                    <div class="col-3">--}}
                        <x-forms.input type="password" name="password_confirmation"></x-forms.input>
{{--                    </div>--}}
                </div>

                @if($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))
                    <hr>
                    <label class="black-label-text" style="font-size: 20px;">Welke rol krijgt de gebruiker?</label>
                    <div class="row">
{{--                        <div class="col-3">--}}
                            <x-forms.single-select :array="$roles" :fields="['name']" name="roles" capitalize="true"></x-forms.single-select>
{{--                        </div>--}}
                    </div>
                @else
                    <input type="hidden" name="roles" value="2">
                @endif
                <a class="float-left" href="{{route('admin.users.index')}}" style="font-size: 30px"><i class="fa-solid fa-backward-step"></i></a>
                <button type="submit" class="btn btn-primary border-0 jg-color-gradient-3 float-right" value="Save">Creëer</button>
            </form>
        </div>
    </div>
</div>
@endsection
