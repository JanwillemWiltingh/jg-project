@extends('layouts.app')

@section('content')
    <div class="crud-user-form fadeInDown" style="left: 20%; width: 60%">
        <h1>Bewerk mijn profiel</h1>
        <div class="card">
            <div class="card-body">
                <form method="get" action="{{ route('profile.update', $user['id']) }}">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="black-label-text" for="password">Nieuw wachtwoord</label>
                                <input type="password" class="form-control" id="password" name="password" aria-describedby="wachtwoord" placeholder="Nieuw wachtwoord">
                                @if($errors->has('password'))
                                    <div class="error">
                                        <label class="warning-label">
                                            {{ $errors->first('password') }}
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="black-label-text" for="password_confirmation">Bevestig nieuw wachtwoord</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" aria-describedby="bevestig_wachtwoord" placeholder="Bevestig nieuw wachtwoord">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="black-label-text" for="current_password">Oud wachtwoord</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Geef oud wachtwoord op">
                            @if($errors->has('current_password'))
                                <div class="error">
                                    <label class="warning-label">
                                        {{ $errors->first('current_password') }}
                                    </label>
                                </div>
                            @endif
                        </div>
                        <label>Pas je wachtwoord aan door je oud wachtwoord te gebruiken</label>
                    </div>
                    <div class="row">
                        {{-- Telefoon nummer input --}}
                        <div class="form-group">
                            <label
                                class="black-label-text"
                                for="phone_number">
                                {{ __('general.'.'phone_number') }}
                            </label>
                            <input type="tel" class="form-control" name="phone_number" pattern="[0-9]{10}" value="{{$user['phone_number']}}" aria-describedby="phone_number" placeholder="{{ __('general.'.'phone_number') }}">
                            <label>Formaat: 0612345678</label>
                            @if($errors->has('phone_number'))
                                <div class="error">
                                    <label class="warning-label">
                                        {{ $errors->first('phone_number') }}
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($user['role_id'] == App\Models\Role::getRoleID('maintainer'))
                        <hr>
                        <label class="black-label-text" style="font-size: 20px;">Geef jezelf een nieuwe rol(indien nodig)</label>
                        <div class="row">
                            <div class="form-group">
                                <label class="black-label-text"
                                       for="roles">
                                    Rol
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
                    @endif

                    <button style="float: right" type="submit" class="btn btn-primary jg-color-3 border-0" value="Save">Opslaan</button>
                    <button class="btn btn-primary jg-color-3 border-0" value="Ga Terug"><a href="{{route('profile.index')}}" style="text-decoration: none; color: white;">Ga terug</a></button>
                </form>
            </div>
        </div>
    </div>
@endsection

