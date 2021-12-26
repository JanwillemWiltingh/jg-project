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
                                <label class="black-label-text" for="wachtwoord">Nieuw wachtwoord</label>
                                <input type="password" class="form-control" id="wachtwoord" name="password" aria-describedby="wachtwoord" placeholder="Nieuw wachtwoord">
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
                            <label class="black-label-text" for="huidig_wachtwoord">Oud wachtwoord</label>
                            <input type="password" class="form-control" id="huidig_wachtwoord" name="huidig_wachtwoord" placeholder="Geef oud wachtwoord op">
                            @if($errors->has('huidig_wachtwoord'))
                                <div class="error">
                                    <label class="warning-label">
                                        {{ $errors->first('huidig_wachtwoord') }}
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
                            <input type="tel" class="form-control" name="telefoon_nummer" pattern="[0-9]{10}" value="{{$user['phone_number']}}" aria-describedby="telefoon_nummer" placeholder="{{ __('general.'.'phone_number') }}">
                            <label>Formaat: 0612345678</label>
                            @if($errors->has('telefoon_nummer'))
                                <div class="error">
                                    <label class="warning-label">
                                        {{ $errors->first('telefoon_nummer') }}
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($user['role_id'] == App\Models\Role::getRoleID('maintainer'))
                        <hr>
                        <label class="black-label-text" style="font-size: 20px;">Geef jezelf een nieuwe rol (indien nodig)</label>
                        <div class="row">
                            <div class="form-group">
                                <label class="black-label-text"
                                       for="rol">
                                    Rol
                                </label>
                                <select class="form-control"
                                        name="rol"
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

                                @if($errors->has('rol'))
                                    <div class="error">
                                        {{ $errors->first('rol') }}
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

