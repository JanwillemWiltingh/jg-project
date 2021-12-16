@extends('layouts.app')

@section('content')
    <script>
        function getUserInfo(firstname, middlename, lastname, email, phone_number, created_at, updated_at, deleted_at, id, roles)
        {
            document.getElementById('firstname').value = firstname;
            document.getElementById('middlename').value = middlename;
            document.getElementById('lastname').value = lastname;
            document.getElementById('email').value = email;
            document.getElementById('admin_user_id_edit').value = id;

            if (roles === "3")
            {
                document.getElementById('solidify_next_week').style.display = "block";
            }
            else
            {
                document.getElementById('solidify_next_week').style.display = "none";
            }

            if(phone_number)
            {
                document.getElementById('phone_number').value = phone_number;
            }
            else
            {
                document.getElementById('phone_number').value = "-";
            }

            if(updated_at)
            {
                document.getElementById('updated_at').value = updated_at;
            }
            else
            {
                document.getElementById('updated_at').value = "-";
            }
            document.getElementById('created_at').value = created_at;

            if(deleted_at)
            {
                document.getElementById('deleted_at').value = deleted_at;
            }
            else
            {
                document.getElementById('deleted_at').value = "-";
            }
        }
        function getRoles(roles){
            document.getElementById('roles').value = roles;
        }
    </script>
    <div class="modal fade" id="showUserInfo" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true" style="margin-top: 5.5%">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gebruiker informatie</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            {{-- Firstname --}}
                            <div class="form-group">
                                <label class="black-label-text" for="firstname">Voornaam</label>
                                <input type="text" class="form-control" id="firstname" value="@if(empty($user['firstname']))NULL @else{{$user['firstname']}} @endif" aria-describedby="voornaam" placeholder="Voornaam" disabled>
                            </div>
                        </div>
                        <div class="col-4">
                            {{-- Middlename --}}
                            <div class="form-group">
                                <label class="black-label-text" for="middlename">Tussenvoegsel</label>
                                <input type="text" class="form-control" id="middlename" value="@if(empty($user['middlename']))NULL @else{{$user['middlename']}} @endif" aria-describedby="middlename" placeholder="Tussenvoegsel" disabled>
                            </div>
                        </div>
                        <div class="col-4">
                            {{-- Lastname --}}
                            <div class="form-group">
                                <label class="black-label-text" for="lastname">Achternaam</label>
                                <input type="text" class="form-control" id="lastname" value="@if(empty($user['lastname']))NULL @else{{$user['lastname']}} @endif" aria-describedby="lastname" placeholder="Achternaam" disabled>
                            </div>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="row">
                        <div class="form-group">
                            <label class="black-label-text" for="email">E-mail</label>
                            <input type="email" class="form-control" id="email" value="@if(empty($user['email']))NULL @else{{$user['email']}} @endif" aria-describedby="email" placeholder="E-mail" disabled>
                        </div>
                    </div>
                    {{-- Telefoonnummer --}}
                    <div class="row">
                        <div class="form-group">
                            <label class="black-label-text" for="phone_number">Telefoonnummer</label>
                            <input type="email" class="form-control" id="phone_number" value="@if(empty($user['phone_number']))NULL @else{{$user['phone_number']}} @endif" aria-describedby="phone_number" placeholder="Telefoonnummer" disabled>
                        </div>
                    </div>
                    {{-- Last updated --}}
                    <div class="row">
                        <div class="form-group">
                            <label class="black-label-text" for="updated_at">Laatst Bijgewerkt</label>
                            <input type="text" class="form-control" id="updated_at" value="@if(empty($user['updated_at'])) - @else{{$user['updated_at']}} @endif" aria-describedby="updated_at" placeholder="Laatst Bijgewerkt" disabled>
                        </div>
                    </div>
                    {{-- Last deleted --}}
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="black-label-text" for="created_at">Gebruiker Gecreëerd</label>
                                <input type="text" class="form-control" id="created_at" value="@if(empty($user['created_at'])) - @else{{$user['created_at']}} @endif" aria-describedby="created_at" placeholder="Gebruiker Gecreëerd" disabled>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="black-label-text" for="deleted_at">Gebruiker Verwijderd</label>
                                <input type="text" class="form-control" id="deleted_at" value="@if(empty($user['deleted_at'])) - @else{{$user['deleted_at']}} @endif" aria-describedby="updated_at" placeholder="Gebruiker Verwijderd" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: -35px !important;">
                        @if($user_session['role_id'] == App\Models\Role::getRoleID('maintainer') && empty($user['deleted_at']))
                            <strong>
                                <button id="go_to_user_edit" class="btn btn-primary jg-color-3 border-0" href="" data-toggle="tooltip" title="Gebruiker Aanpassen">Bewerk gebruiker</button>
                            </strong>
                        @elseif($user_session['role_id'] == App\Models\Role::getRoleID('admin') && empty($user['deleted_at']))
                            <i class="fa-solid fa-user-lock"></i>
                        @endif
                        <strong>
                            <button id="solidify_next_week" style="float: right !important; bottom: 45px" class="btn btn-primary jg-color-3 border-0" href="" data-toggle="tooltip" title="Gebruiker Aanpassen">Zet rooster vast</button>
                        </strong>
                    </div>
                    <input type="hidden" id="admin_user_id_edit">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showUserCreate" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true" style="margin-top: 5%">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gebruiker toevoegen</h5>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('admin.users.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-4">
                                {{-- Firstname --}}
                                <x-forms.input type="text" name="firstname"></x-forms.input>
                            </div>
                            <div class="col-4">
                                {{-- Middlename --}}
                                <x-forms.input type="text" name="middlename"></x-forms.input>
                            </div>
                            <div class="col-4">
                                {{-- Lastname --}}
                                <x-forms.input type="text" name="lastname"></x-forms.input>
                            </div>
                        </div>
                        <div class="row">
                            <x-forms.input type="email" name="email"></x-forms.input>
                        </div>
                        <div class="row">
                            {{-- Telefoon nummer input --}}
                            <div class="form-group">
                                <label
                                    class="black-label-text"
                                    for="phone_number">
                                    {{ __('general.'.'phone_number') }}
                                </label>
                                <input type="tel" class="form-control" name="phone_number" pattern="[0-9]{10}" value="{{ old('phone_number') ?? $value ?? null }}" aria-describedby="phone_number" placeholder="{{ __('general.'.'phone_number') }}">
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
                                                    @if($role['id'] == '3')
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
                        <button style="float: right" type="submit" class="btn btn-primary jg-color-3 border-0" value="Save">Toevoegen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<div class="fadeInDown crud-table">

    <text class="crud-user-form-title icon-color">Alle gebruikers</text><br>
    <div style="display: inline-block">
        <input type="text" id="search" class="form-control" placeholder="Zoek..." style="width: 100%">
    </div>
    <div style="display: inline-block">
        <a class="btn btn-primary jg-color-3 border-0" style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserCreate" onclick="getRoles('{{$roles}}')">Nieuwe gebruiker <i class="fa-solid fa-plus"></i></a>
    </div>

    <br>
    <table class="table table-hover" id="user_crud">
        <thead>
        <tr>
            <th scope="col"><strong>Voornaam</strong></th>
            <th scope="col"><strong>Tussenvoegsel</strong></th>
            <th scope="col"><strong>Achternaam</strong></th>
            <th scope="col"><strong>Telefoonnummer</strong></th>
            <th scope="col"><strong>Actief</strong></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr class="{{ $user->isCurrentUser() }}">
                <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$user['firstname']}}', '{{$user['middlename']}}','{{$user['lastname']}}', '{{$user['email']}}','{{$user['phone_number']}}', '{{$user['created_at']}}','{{$user['updated_at']}}', '{{$user['deleted_at']}}','{{$user['id']}}','{{$user['role_id']}}')">{{$user['firstname']}}</td>
                <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$user['firstname']}}', '{{$user['middlename']}}','{{$user['lastname']}}', '{{$user['email']}}','{{$user['phone_number']}}', '{{$user['created_at']}}','{{$user['updated_at']}}', '{{$user['deleted_at']}}','{{$user['id']}}','{{$user['role_id']}}')">{{ $user['middlename'] ?? '' }}</td>
                <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$user['firstname']}}', '{{$user['middlename']}}','{{$user['lastname']}}', '{{$user['email']}}','{{$user['phone_number']}}', '{{$user['created_at']}}','{{$user['updated_at']}}', '{{$user['deleted_at']}}','{{$user['id']}}','{{$user['role_id']}}')">{{$user['lastname']}}</td>
                <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$user['firstname']}}', '{{$user['middlename']}}','{{$user['lastname']}}', '{{$user['email']}}','{{$user['phone_number']}}', '{{$user['created_at']}}','{{$user['updated_at']}}', '{{$user['deleted_at']}}','{{$user['id']}}','{{$user['role_id']}}')">{{$user['phone_number']}}</td>
                <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$user['firstname']}}', '{{$user['middlename']}}','{{$user['lastname']}}', '{{$user['email']}}','{{$user['phone_number']}}', '{{$user['created_at']}}','{{$user['updated_at']}}', '{{$user['deleted_at']}}', '{{$user['id']}}','{{$user['role_id']}}')">
                    @if(empty($user['deleted_at']))
                        Ja
                    @else
                        Nee
                    @endif
                </td>
                <td>
                    @if($user_session['role_id'] == App\Models\Role::getRoleID('admin'))
                        @if($user['role_id'] != App\Models\Role::getRoleID('employee'))
                            <i class="fa-solid fa-user-lock"></i>
                        @else
                            <strong>
                                <a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}">
                                    @if($user['role_id'] != App\Models\Role::getRoleID('maintainer'))
                                        @if(empty($user['deleted_at']))
                                            <a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Verwijderen"><i class="fa-solid fa-user-slash"></i></a>
                                        @else
                                            <a class="table-label-green" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Herstellen"><i class="fa-solid fa-user-check"></i></a>
                                        @endif
                                    @endif
                                </a>
                            </strong>
                        @endif
                    @elseif($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))
                        <strong>
                            @if($user['role_id'] != App\Models\Role::getRoleID('maintainer'))
                                @if(empty($user['deleted_at']))
                                    {{--                                    <a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" onclick="if(confirm('weet je zeker dat je deze gebruiker wilt verwijderen?')) true;return false" title="Gebruiker Verwijderen"><i class="fa-solid fa-user-slash"></i></a>--}}
                                    <a class="table-label-red delete-link" data-name="{{ str_replace('  ', ' ', $user['firstname']." ".$user['middlename']." ".$user['lastname']) }}" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip"><i class="fa-solid fa-user-slash"></i></a>
                                @else
                                    <a class="table-label-green restore-link" data-name="{{ str_replace('  ', ' ', $user['firstname']." ".$user['middlename']." ".$user['lastname']) }}" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Herstellen"><i class="fa-solid fa-user-check"></i><a/>
                                        @endif
                                        @elseif($user_session['role_id'] == App\Models\Role::getRoleID('maintainer') && $user['role_id'] != App\Models\Role::getRoleID('maintainer'))
                                            @if(empty($user['deleted_at']))
                                                <a class="table-label-red delete-link" data-name="{{ str_replace('  ', ' ', $user['firstname']." ".$user['middlename']." ".$user['lastname']) }}" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Verwijderen"><i class="fa-solid fa-user-slash"></i></a>
                                            @else
                                                <a class="table-label-green restore-link" data-name="{{ str_replace('  ', ' ', $user['firstname']." ".$user['middlename']." ".$user['lastname']) }}" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Herstellen"><i class="fa-solid fa-user-check"></i><a/>
                                                    @endif
                                                    @else
                                                        <i class="fa-solid fa-user-lock"></i>
                                @endif
                        </strong>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
    <hr>
{{--  disabled user table  --}}
    <div class="fadeInDown crud-table">
        <text class="crud-user-form-title icon-color">Uitgezette gebruikers</text><br>
        <table class="table table-hover" id="user_crud">
        <tbody class="labels">
            <tr>
                <td colspan="6">
                    <label for="deleted_users" style="font-size: 15px;">Klik hier om uitgezette gebruikers te laten zien of verbergen</label>
                    <input type="checkbox" name="deleted_users" id="deleted_users" data-toggle="toggle">
                </td>
            </tr>
        </tbody>
        <tbody class="show">
        <tr>
            <th scope="col"><strong>Voornaam</strong></th>
            <th scope="col"><strong>Tussenvoegsel</strong></th>
            <th scope="col"><strong>Achternaam</strong></th>
            <th scope="col"><strong>Telefoonnummer</strong></th>
            <th scope="col"><strong>Actief</strong></th>
            <th scope="col"></th>
        </tr>
        {{--Loop each user to show in a table--}}
        @foreach($deleted_users as $deleted_user)
            <tr class="{{ $deleted_user->isCurrentUser() }}">
                <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$deleted_user['firstname']}}', '{{$deleted_user['middlename']}}','{{$deleted_user['lastname']}}', '{{$deleted_user['email']}}','{{$deleted_user['phone_number']}}', '{{$deleted_user['created_at']}}','{{$deleted_user['updated_at']}}', '{{$deleted_user['deleted_at']}}','{{$deleted_user['id']}}')">{{$deleted_user['firstname']}}</td>
                <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$deleted_user['firstname']}}', '{{$deleted_user['middlename']}}','{{$deleted_user['lastname']}}', '{{$deleted_user['email']}}','{{$deleted_user['phone_number']}}', '{{$deleted_user['created_at']}}','{{$deleted_user['updated_at']}}', '{{$deleted_user['deleted_at']}}','{{$deleted_user['id']}}')">{{ $deleted_user['middlename'] ?? '' }}</td>
                <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$deleted_user['firstname']}}', '{{$deleted_user['middlename']}}','{{$deleted_user['lastname']}}', '{{$deleted_user['email']}}','{{$deleted_user['phone_number']}}', '{{$deleted_user['created_at']}}','{{$deleted_user['updated_at']}}', '{{$deleted_user['deleted_at']}}','{{$deleted_user['id']}}')">{{$deleted_user['lastname']}}</td>
                <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$deleted_user['firstname']}}', '{{$deleted_user['middlename']}}','{{$deleted_user['lastname']}}', '{{$deleted_user['email']}}','{{$deleted_user['phone_number']}}', '{{$deleted_user['created_at']}}','{{$deleted_user['updated_at']}}', '{{$deleted_user['deleted_at']}}','{{$deleted_user['id']}}')">{{$deleted_user['phone_number']}}</td>
                <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$deleted_user['firstname']}}', '{{$deleted_user['middlename']}}','{{$deleted_user['lastname']}}', '{{$deleted_user['email']}}','{{$deleted_user['phone_number']}}', '{{$deleted_user['created_at']}}','{{$deleted_user['updated_at']}}', '{{$deleted_user['deleted_at']}}', '{{$deleted_user['id']}}',)">
                    @if(empty($deleted_user['deleted_at']))
                        Ja
                    @else
                        Nee
                    @endif
                </td>
                <td>
                    @if($user_session['role_id'] == App\Models\Role::getRoleID('admin'))
                        @if($deleted_user['role_id'] != App\Models\Role::getRoleID('employee'))
                            <i class="fa-solid fa-user-lock"></i>
                        @else
                            <strong>
                                <a class="table-label-red" href="{{route('admin.users.destroy',$deleted_user['id'])}}">
                                    @if($deleted_user['role_id'] != App\Models\Role::getRoleID('maintainer'))
                                        @if(empty($deleted_user['deleted_at']))
                                            <a class="table-label-red" href="{{route('admin.users.destroy',$deleted_user['id'])}}" data-toggle="tooltip" title="Gebruiker Verwijderen"><i class="fa-solid fa-user-slash"></i></a>
                                        @else
                                            <a class="table-label-green" href="{{route('admin.users.destroy',$deleted_user['id'])}}" data-toggle="tooltip" title="Gebruiker Herstellen"><i class="fa-solid fa-user-check"></i></a>
                                        @endif
                                    @endif
                                </a>
                            </strong>
                        @endif
                    @elseif($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))
                        <strong>
                            @if($deleted_user['role_id'] != App\Models\Role::getRoleID('maintainer'))
                                @if(empty($deleted_user['deleted_at']))
                                    {{--                                    <a class="table-label-red" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" onclick="if(confirm('weet je zeker dat je deze gebruiker wilt verwijderen?')) true;return false" title="Gebruiker Verwijderen"><i class="fa-solid fa-user-slash"></i></a>--}}
                                    <a class="table-label-red delete-link" data-name="{{ str_replace('  ', ' ', $deleted_user['firstname']." ".$deleted_user['middlename']." ".$deleted_user['lastname']) }}" href="{{route('admin.users.destroy',$deleted_user['id'])}}" data-toggle="tooltip"><i class="fa-solid fa-user-slash"></i></a>
                                @else
                                    <a class="table-label-green restore-link" data-name="{{ str_replace('  ', ' ', $deleted_user['firstname']." ".$deleted_user['middlename']." ".$deleted_user['lastname']) }}" href="{{route('admin.users.destroy',$deleted_user['id'])}}" data-toggle="tooltip" title="Gebruiker Herstellen"><i class="fa-solid fa-user-check"></i><a/>
                                    @endif
                                    @elseif($user_session['role_id'] == App\Models\Role::getRoleID('maintainer') && $deleted_user['role_id'] != App\Models\Role::getRoleID('maintainer'))
                                        @if(empty($deleted_user['deleted_at']))
                                            <a class="table-label-red delete-link" data-name="{{ str_replace('  ', ' ', $deleted_user['firstname']." ".$deleted_user['middlename']." ".$deleted_user['lastname']) }}" href="{{route('admin.users.destroy',$deleted_user['id'])}}" data-toggle="tooltip" title="Gebruiker Verwijderen"><i class="fa-solid fa-user-slash"></i></a>
                                        @else
                                            <a class="table-label-green restore-link" data-name="{{ str_replace('  ', ' ', $deleted_user['firstname']." ".$deleted_user['middlename']." ".$deleted_user['lastname']) }}" href="{{route('admin.users.destroy',$deleted_user['id'])}}" data-toggle="tooltip" title="Gebruiker Herstellen"><i class="fa-solid fa-user-check"></i><a/>
                                        @endif
                                    @else
                                    <i class="fa-solid fa-user-lock"></i>
                                @endif
                        </strong>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
    <br><br><br><br><br><br>
    <script>
        $('.delete-link').on('click', function () {
            // https://sweetalert.js.org/docs/
            event.preventDefault();

            swal({
                title: "Pas Op!",
                text: "Weet u zeker dat u " + $(this).attr('data-name') + " wilt uitzetten?",
                icon: "warning",
                dangerMode: true,
                buttons: {
                    cancel: {
                        text: "Annuleren",
                        value: null,
                        visible: true,
                        className: "swal-cancel-button",
                        closeModal: true,
                    },
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "swal-confirm-button",
                        closeModal: true
                    },
                },
                closeOnEscape: true,
            });

            const href = this.href;
            setInterval(function (){
                $('.swal-confirm-button').on('click', function () {
                    window.location = href;
                    clearInterval();
                });

                $('.swal-cancel-button').on('click', function () {
                    console.log('Deletion Canceled');
                    clearInterval();
                });
            }, 500);
        });

        $('.restore-link').on('click', function () {
            // https://sweetalert.js.org/docs/
            event.preventDefault();

            swal({
                title: "Pas Op!",
                text: "Weet u zeker dat u " + $(this).attr('data-name') + " wilt herstellen?",
                icon: "warning",
                dangerMode: true,
                buttons: {
                    cancel: {
                        text: "Annuleren",
                        value: null,
                        visible: true,
                        className: "swal-cancel-button",
                        closeModal: true,
                    },
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "swal-confirm-button",
                        closeModal: true
                    },
                },
                closeOnEscape: true,
            });

            const href = this.href;
            setInterval(function (){
                $('.swal-confirm-button').on('click', function () {
                    window.location = href;
                    clearInterval();
                });

                $('.swal-cancel-button').on('click', function () {
                    console.log('Restoration Canceled');
                    clearInterval();
                });
            }, 500);
        });
    </script>

@endsection
