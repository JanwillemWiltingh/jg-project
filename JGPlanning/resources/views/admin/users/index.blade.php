@extends('layouts.app')

@section('content')
    <script>
        function getUserInfo(firstname, middlename, lastname, email, phone_number, created_at, updated_at, deleted_at, id)
        {
            console.log(phone_number);
            document.getElementById('firstname').value = firstname;
            document.getElementById('middlename').value = middlename;
            document.getElementById('lastname').value = lastname;
            document.getElementById('email').value = email;
            document.getElementById('admin_user_id_edit').value = id;
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
    </script>
    <div class="modal fade" id="showUserInfo" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true" style="margin-top: 6%">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gebruikers informatie</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            {{-- Firstname --}}
                            <div class="form-group">
                                <label class="black-label-text" for="firstname">Voornaam</label>
                                <input type="text" class="form-control" id="firstname" value="@if(empty($user['firstname']))NULL @else{{$user['firstname']}} @endif" aria-describedby="firstname" placeholder="Voornaam" disabled>
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
                    <div class="row"
                        @if($user_session['role_id'] == App\Models\Role::getRoleID('maintainer') && empty($user['deleted_at']))
                                <strong>
                                    <a id="go_to_user_edit" class="table-label" href="#" data-toggle="tooltip" title="Gebruiker Aanpassen"><i class="fa-solid fa-user-pen icon-color"></i></a>
                                </strong>
                        @else
                            <i class="fa-solid fa-user-lock"></i>
                        @endif
                    </div>

                    <input type="hidden" id="admin_user_id_edit">
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
        <a class="btn btn-primary jg-color-3 border-0" href="{{route('admin.users.create')}}" data-toggle="tooltip" title="Gebruiker Toevoegen">Nieuwe gebruiker <i class="fa-solid fa-plus"></i></a>
    </div>

    <br>
    <table class="table table-hover" id="user_crud">
        <thead>
        <tr>
{{--            <th scope="col"><strong>#</strong></th>--}}
            <th scope="col"><strong>Voornaam</strong></th>
            <th scope="col"><strong>Tussenvoegsel</strong></th>
            <th scope="col"><strong>Achternaam</strong></th>
{{--            <th scope="col"><strong>E-mail</strong></th>--}}
            <th scope="col"><strong>Telefoonnummer</strong></th>
{{--            <th scope="col"><strong>Rol</strong></th>--}}
            <th scope="col"><strong>Actief</strong></th>
            <th scope="col"></th>
            <th scope="col"></th>
{{--            <th scope="col"></th>--}}
        </tr>
        </thead>
        <tbody>
        {{--Loop each user to show in a table--}}
        @foreach($users as $user)
            <tr class="{{ $user->isCurrentUser() }}">
{{--                <th scope="row">{{ $loop->index + 1 }}</th>--}}
                {{--Check the email from the current user and the email in the database to show who is selected(logged in)--}}
                <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$user['firstname']}}', '{{$user['middlename']}}','{{$user['lastname']}}', '{{$user['email']}}','{{$user['phone_number']}}', '{{$user['created_at']}}','{{$user['updated_at']}}', '{{$user['deleted_at']}}','{{$user['id']}}')">{{$user['firstname']}}</td>
                <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$user['firstname']}}', '{{$user['middlename']}}','{{$user['lastname']}}', '{{$user['email']}}','{{$user['phone_number']}}', '{{$user['created_at']}}','{{$user['updated_at']}}', '{{$user['deleted_at']}}','{{$user['id']}}')">{{ $user['middlename'] ?? '' }}</td>
                <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$user['firstname']}}', '{{$user['middlename']}}','{{$user['lastname']}}', '{{$user['email']}}','{{$user['phone_number']}}', '{{$user['created_at']}}','{{$user['updated_at']}}', '{{$user['deleted_at']}}','{{$user['id']}}')">{{$user['lastname']}}</td>

{{--                <td>{{$user['email']}}</td>--}}
                <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$user['firstname']}}', '{{$user['middlename']}}','{{$user['lastname']}}', '{{$user['email']}}','{{$user['phone_number']}}', '{{$user['created_at']}}','{{$user['updated_at']}}', '{{$user['deleted_at']}}','{{$user['id']}}')">{{$user['phone_number']}}</td>

                {{--Big letter maintainer--}}
{{--                <td>@if($user['role_id'] == App\Models\Role::getRoleID('maintainer'))<strong>{{__('general.' .$user->role()->get()->first()->name)}}</strong> @else {{__('general.' .$user->role()->get()->first()->name)}} @endif</td>--}}

                {{--Shows if the user is soft-deleted(active) or not--}}
                <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$user['firstname']}}', '{{$user['middlename']}}','{{$user['lastname']}}', '{{$user['email']}}','{{$user['phone_number']}}', '{{$user['created_at']}}','{{$user['updated_at']}}', '{{$user['deleted_at']}}', '{{$user['id']}}',)">
                    @if(empty($user['deleted_at']))
                        Ja
                    @else
                        Nee
                    @endif
                </td>

                {{-- Check if the user is allowed to edit the user --}}
{{--                <td>--}}
{{--                    @if($user_session['role_id'] == App\Models\Role::getRoleID('admin') && empty($user['deleted_at']))--}}
{{--                        @if($user['role_id'] != App\Models\Role::getRoleID('employee'))--}}
{{--                            <i class="fa-solid fa-user-lock"></i>--}}
{{--                        @else--}}
{{--                            <strong>--}}
{{--                                <a class="table-label" href="{{route('admin.users.edit',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Aanpassen"><i class="fa-solid fa-user-pen icon-color"></i></a>--}}
{{--                            </strong>--}}
{{--                        @endif--}}
{{--                    @elseif($user_session['role_id'] == App\Models\Role::getRoleID('maintainer'))--}}
{{--                        @if($user['role_id'] != App\Models\Role::getRoleID('maintainer') && empty($user['deleted_at']))--}}
{{--                            <strong>--}}
{{--                                <a class="table-label" href="{{route('admin.users.edit',$user['id'])}}" data-toggle="tooltip" title="Gebruiker aanpassen"><i class="fa-solid fa-user-pen icon-color"></i></a>--}}
{{--                            </strong>--}}
{{--                        @else--}}
{{--                            <i class="fa-solid fa-user-lock"></i>--}}
{{--                        @endif--}}
{{--                    @endif--}}
{{--                </td>--}}

                {{-- Check if the user is allowed to delete the user --}}
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
                                    <a class="table-label-red" id="delete-link" data-name="{{ str_replace('  ', ' ', $user['firstname']." ".$user['middlename']." ".$user['lastname']) }}" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip"><i class="fa-solid fa-user-slash"></i></a>
                                @else
                                    <a class="table-label-green" id="restore-link" data-name="{{ str_replace('  ', ' ', $user['firstname']." ".$user['middlename']." ".$user['lastname']) }}" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Herstellen"><i class="fa-solid fa-user-check"></i><a/>
                                @endif
                            @elseif($user_session['role_id'] == App\Models\Role::getRoleID('maintainer') && $user['role_id'] != App\Models\Role::getRoleID('maintainer'))
                                @if(empty($user['deleted_at']))
                                    <a class="table-label-red" id="delete-link" data-name="{{ str_replace('  ', ' ', $user['firstname']." ".$user['middlename']." ".$user['lastname']) }}" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Verwijderen"><i class="fa-solid fa-user-slash"></i></a>
                                @else
                                    <a class="table-label-green" id="restore-link" data-name="{{ str_replace('  ', ' ', $user['firstname']." ".$user['middlename']." ".$user['lastname']) }}" href="{{route('admin.users.destroy',$user['id'])}}" data-toggle="tooltip" title="Gebruiker Herstellen"><i class="fa-solid fa-user-check"></i><a/>
                                @endif
                            @else
                                <i class="fa-solid fa-user-lock"></i>
                            @endif
                        </strong>
                    @endif
                </td>
{{--                <td>--}}
{{--                    <a class="table-label" href="{{route('admin.users.show',$user['id'])}}" data-toggle="tooltip" title="Bekijken"><i class="fa-solid fa-user-gear icon-color"></i></a>--}}
{{--                </td>--}}
            </tr>
        @endforeach
        </tbody>
    </table>

    <script>
        $('#delete-link').on('click', function () {
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

        $('#restore-link').on('click', function () {
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
