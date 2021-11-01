@extends('layouts.app')

@section('content')
    <h1>Maak een nieuwe Gebruiker aan <a href="{{route('admin.users.index')}}" style="font-size: 30px;"><i class="fa-solid fa-backward-step"></i></a></h1>
    <form method="get" action="{{ route('admin.users.store') }}">
        <div class="row">
            <div class="col-3">
                <x-forms.input type="text" name="firstname"></x-forms.input>
            </div>

            <div class="col-3">
                <x-forms.input type="text" name="middlename"></x-forms.input>
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
