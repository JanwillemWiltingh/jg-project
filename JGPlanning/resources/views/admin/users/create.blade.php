@extends('layouts.app')

@section('content')
<div class="crud-user-form fadeInDown">
    <text class="crud-user-form-title icon-color">Maak een nieuwe Gebruiker aan </text>
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
                <x-forms.input type="text" name="lastname"></x-forms.input>
            </div>
        <div class="row">
            <x-forms.input type="email" name="email"></x-forms.input>
        </div>

        @if($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))
            <hr>
            <label class="black-label-text" style="font-size: 20px;">Welke rol krijgt de gebruiker?</label>
            <div class="row">
                <div class="form-group">
                    <label class="black-label-text"
                           for="roles">
                        Rollen
                    </label>
                    <select class="form-control"
                            name="roles"
                            id="roles">
                        @foreach($roles as $role)
                            <option value="{{$role['id']}}"
                                    @if(old('roles') == $role['id'])
                                    selected
                                @endif>
                                {{__('general.' .$role['name'])}}
                            </option>
                        @endforeach
                    </select>

                    @if($errors->has('roles'))
                        <div class="error">
                            {{ $errors->first('roles') }}
                        </div>
                    @endif
                </div>

            </div>
        @else
            <input type="hidden" name="roles" value="2">
        @endif

        <button type="submit" class="btn btn-primary jg-color-3 border-0" value="Save">Creëer</button>

            </form>
        </div>
@endsection
