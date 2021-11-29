@extends('layouts.app')

@section('content')
    <div class="crud-user-form fadeInDown">
        <h1>Bewerk Gebruiker <a href="{{route('admin.users.index')}}" style="font-size: 30px;"><i class="fa-solid fa-backward-step icon-color"></i></a></h1>
        <div class="card">
            <div class="card-body">
                <form method="get" action="{{ route('admin.users.update', $user['id']) }}">
                    <div class="row">
                        {{--                    <div class="col-3">--}}
                        <x-forms.input type="text" value="{{$user['firstname']}}" name="firstname"></x-forms.input>
                        {{--                    </div>--}}
                    </div>

                    <div class="row">
                        {{--                    <div class="col-3">--}}
                        <x-forms.input type="text" value="{{$user['middlename']}}" name="middlename"></x-forms.input>
                        {{--                    </div>--}}
                    </div>

                    <div class="row">
                        <x-forms.input type="text" value="{{$user['lastname']}}" name="lastname"></x-forms.input>
                    </div>
                    <div class="row">
                        <x-forms.input type="email" value="{{$user['email']}}" name="email"></x-forms.input>
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

                    <button type="submit" class="btn btn-primary jg-color-3 border-0" value="Save">Bewerk</button>

                </form>
            </div>
        </div>
    </div>
@endsection
