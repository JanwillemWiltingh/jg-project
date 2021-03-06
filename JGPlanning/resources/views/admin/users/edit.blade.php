@extends('layouts.app')

@section('content')
    <div class="crud-user-form fadeInDown" style="left: 20%; width: 60%">
        <text class="crud-user-form-title icon-color">Bewerk gebruiker</text>
        <div class="card">
            <div class="card-body">
                <form method="get" action="{{ route('admin.users.update', $user['id']) }}">
                    <div class="row">
                        <div class="col-4">
                        {{-- Firstname --}}
                            <x-forms.input type="text" value="{{$user['firstname']}}" name="firstname"></x-forms.input>
                        </div>
                        <div class="col-4">
                        {{-- Middlename --}}
                            <x-forms.input type="text" value="{{$user['middlename']}}" name="middlename"></x-forms.input>
                        </div>
                        <div class="col-4">
                        {{-- Lastname --}}
                            <x-forms.input type="text" value="{{$user['lastname']}}" name="lastname"></x-forms.input>
                        </div>
                    </div>
                    <div class="row">
                        <x-forms.input type="email" value="{{$user['email']}}" name="email"></x-forms.input>
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
                            {{--                            <input type="tel" class="form-control" name="phone_number" value="{{ old('phone_number') ?? $value ?? null }}" aria-describedby="phone_number" placeholder="{{ __('general.'.'phone_number') }}">--}}

                            @if($errors->has('phone_number'))
                                <div class="error">
                                    <label class="warning-label">
                                        {{ $errors->first('phone_number') }}
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))
                        <hr>
                        <label class="black-label-text" style="font-size: 20px;">Welke rol krijgt de gebruiker?</label>
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
                                                @if(old('roles') == $role['id'] || $user['role_id'] == $role['id'])
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

                    <button style="float: right" type="submit" class="btn btn-primary jg-color-3 border-0" value="Save">Bewerk</button>
                    <button class="btn btn-primary jg-color-3 border-0" value="Ga Terug"><a href="{{route('admin.users.index')}}" style="text-decoration: none; color: white;">Ga terug</a></button>
                </form>
            </div>
        </div>
    </div>
@endsection
