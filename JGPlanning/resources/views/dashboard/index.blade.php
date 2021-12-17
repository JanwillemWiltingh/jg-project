@extends('layouts.app')

@section('content')
{{--    Cards should imitate this: https://codepen.io/lesliesamafful/pen/oNXgmBG?editors=1010   --}}
    <div class="row" >
        <div class="col-12">
            <div class="
        @if($browser->isMobile())
                dashboard-welkom-mobile
        @else
                dashboard-welkom
        @endif
                ">
                <h1>Welkom </h1>
                <a>{{$user_session['firstname']}} <i class="fa-solid fa-rocket"></i></a>
            </div>
        </div>
    </div>

    <div @if($browser->isMobile())style="width: 105% !important;" @else class="row"@endif>
        @if($user_session['role_id'] == \App\Models\Role::getRoleID('employee'))
        @if(!$browser->isMobile())
            <div class=" col-4">
                <div class="card">
                    <div class="card-body gradient-dashboard">
                        <h3>Gewerkte uren {{$now->format('F')}}</h3>
                        <hr>
                        <div class="media align-items-stretch" >
                            <div class="align-self-center">
                                <i class="far fa-clock fa-4x"></i>
                            </div>
                            <div class="media-body pl-3">
                                <h4 class="dashboard-title-hours">Uren gewerkt</h4>
                                <span class="dashboard-title-hours">{{ $now->format('F') }} {{ $now->format('Y') }}</span>
                            </div>
                            <div class="align-self-center">
                                <h1 class="dashboard-hours">{{ sprintf('%.2f', $user_session->WorkedInAMonthInHours($now->month, 2)) }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(!$browser->isMobile())
            <div class="col-4 ">
                <div class="card">
                    <div class="card-body gradient-dashboard">
                        <h3>Gewerkte uren week</h3>
                        <hr>
                        <div class="media align-items-stretch">
                            <div class="align-self-center">
                                <i class="far fa-clock fa-4x font-weight-lighter"></i>
                            </div>
                            <div class="media-body pl-3">
                                <h4 class="dashboard-title-hours">Uren gewerkt</h4>
                                <span class="dashboard-title-hours">Week {{ $now->weekOfYear }}, {{ $now->format('Y') }}</span>
                            </div>
                            <div class="align-self-center">
                                <h1 class="dashboard-title-hours">{{ sprintf('%.2f', $user_session->workedInAWeekInHours($now->weekOfYear, 2)) }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(!$browser->isMobile())
            <div class="  col-4 ">
                <div class="card">
                    <div class="card-body gradient-dashboard">
                        <h3>Gewerkte uren vandaag</h3>
                        <hr>
                        <div class="media align-items-stretch">
                            <div class="align-self-center">
                                <i class="far fa-clock fa-4x font-weight-lighter"></i>
                            </div>
                            <div class="media-body pl-3">
                                <h4 class="dashboard-title-hours">Uren gewerkt</h4>
                                <span class="dashboard-title-hours">{{ $now->format('j F, Y') }}</span>
                            </div>
                            <div class="align-self-center">
                                <h1 class="dashboard-title-hours">{{ sprintf('%.2f', $user_session->workedInADayInHours($now->year, $now->month, $now->day, 2))  }}/{{ sprintf('%.2f', $user_session->plannedWorkADayInHours($now->year, $now->weekOfYear, $now->dayOfWeek)) }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="@if(!$browser->isMobile()) col-6 @endif">
            <div class="card">
                <div class="card-body ">
                    <form id="klok-form" action="{{ route('dashboard.clock') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
{{--                                    <label for="comment" class="form-label">Opmerking</label>--}}
                                    <label style="float: left !important;">
                                        <text id="count"></text><text>/ 150</text>
                                    </label>
                                    <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Reden van te laat zijn: Bijv, Bus te laat, Afspraak bij tandarts, Afspraak bij huisarts, Etc." maxlength="150
" @if($start == False) @else DISABLED @endif></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @if($start)
                                    <button id="clock_button" data-enable_at="{{ $enable_time ?? 'null' }}" type="submit" class="btn btn-dark float-right" style="background: #CB6827 !important; border-color:  #CB6827 !important;">Uitklokken</button>
                                @else
                                    <button id="clock_button" data-enable_at="{{ $enable_time ?? 'null'}}" type="submit" class="btn btn-dark float-right jg-color-2 border-0" @if(!$allowed) DISABLED @endif>Inklokken</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if(!$browser->isMobile())
        <div class="col-6">
            <div class="row">
                <div class="col-12" style="margin-bottom: -31px !important">
                    <div class="card" style="height: 72% !important;">
                        <div class="card-body gradient-dashboard" id="easter_egg_title">
                            <div class="media align-items-stretch">
                                <div class="align-self-center">
                                    <i class="far fa-clock fa-4x font-weight-lighter"></i>
                                </div>
                                <div class="media-body pl-3">
                                    <h4>Vandaag</h4>
                                    @if($user_session->getRoosterFromToday() != null)
                                        <span>{{ $now->format('d F Y') }}</span>
                                    @else
                                        <span>Geen rooster voor vandaag</span>
                                    @endif
                                </div>
                                <div class="align-self-center">
                                    <h1>
                                        @if($user_session->getRoosterFromToday() != null)
                                            {{ Carbon\Carbon::parse($user_session->getRoosterFromToday()['start_time'])->format('H:i') }} - {{ Carbon\Carbon::parse($user_session->getRoosterFromToday()['end_time'])->format('H:i') }}
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card" style="height: 72% !important;">
                        <div class="card-body gradient-dashboard">
                            <div class="media align-items-stretch">
                                <div class="align-self-center">
                                    <i class="far fa-clock fa-4x"></i>
                                </div>
                                <div class="media-body pl-3">
                                    <h4>Volgende werkdag</h4>
{{--                                    <h4>@if($user->getNextRooster() == null) @else {{ App\Models\Availability::WEEK_DAYS[$user->getNextRooster()['weekdays']] }} @endif</h4>--}}

                                    @if($user_session->getNextRooster() == null)
                                        <span>Geen nieuw aankomend rooster</span>
                                    @else
                                        <span>{{ $now->addDay(1)->format('d F Y') }}</span>
                                    @endif
                                </div>
                                <div class="align-self-center">
                                    @if($user_session->getNextRooster() == null)
                                        <h1></h1>
                                    @else
                                        <h1>{{ Carbon\Carbon::parse($user_session->getNextRooster()['start_time'])->format('H:i') }} - {{ Carbon\Carbon::parse($user_session->getNextRooster()['end_time'])->format('H:i') }}</h1>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif
        @if($user_session['role_id'] == \App\Models\Role::getRoleID('maintainer'))
        {{-- Admin dashboard --}}
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                    <input type="text" class="form-control" id="phone_number" value="@if(empty($user['phone_number']))NULL @else{{$user['phone_number']}} @endif" aria-describedby="phone_number" placeholder="Telefoonnummer" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <strong>
                                    <button id="solidify_next_week" style="float: right !important;" class="btn btn-primary jg-color-3 border-0" href="" data-toggle="tooltip" title="Gebruiker Aanpassen">Zet rooster vast</button>
                                </strong>
                            </div>
                            <input type="hidden" id="admin_user_id_edit">
                        </div>
                    </div>
                </div>
            </div>

            <div class="crud-table">
                <br>
                <table class="table table-hover" id="user_crud">
                    <thead>
                    <tr>
                        <th scope="col"><strong>Naam</strong></th>
                        <th scope="col"><strong>Werktijden</strong></th>
                        <th scope="col"><strong>Ingeklokt om</strong></th>
                        <th scope="col"><strong>Uitgeklokt om</strong></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        @if($user->getRoosterFromToday() != null)
                        <tr class="
                            @if($user->isClockedIn() && $clocks->where('user_id', $user['id'])->where('date', $now->format('Y-m-d'))->first()->start_time > Carbon\Carbon::parse($user->getRoosterFromToday()['start_time'])->format('H:i'))
                                table-danger
                            @elseif($user->isClockedIn() == false && Carbon\Carbon::parse($user->getRoosterFromToday()['end_time'])->format('H:i') < Carbon\Carbon::parse($user->getRoosterFromToday()['end_time'])->format('H:i') )
                                table-danger
                            @elseif($user->isClockedIn() == false && empty($clocks->where('user_id', $user['id'])->where('date', $now->format('Y-m-d'))->first()->end_time))
                                table-danger
                            @else
                                table-success
                            @endif">
                            <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$user['firstname']}}', '{{$user['middlename']}}','{{$user['lastname']}}', '{{$user['email']}}','{{$user['phone_number']}}','{{$user['id']}}','{{$user['role_id']}}')">{{$user['firstname']}}</td>
                            <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$user['firstname']}}', '{{$user['middlename']}}','{{$user['lastname']}}', '{{$user['email']}}','{{$user['phone_number']}}','{{$user['id']}}','{{$user['role_id']}}')">
                                    @if(Carbon\Carbon::parse($user->getRoosterFromToday()['start_time'])->format('H:i') != null)
                                        {{ Carbon\Carbon::parse($user->getRoosterFromToday()['start_time'])->format('H:i') }} - {{ Carbon\Carbon::parse($user->getRoosterFromToday()['end_time'])->format('H:i') }}
                                @endif
                            </td>
                            <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$user['firstname']}}', '{{$user['middlename']}}','{{$user['lastname']}}', '{{$user['email']}}','{{$user['phone_number']}}','{{$user['id']}}','{{$user['role_id']}}')">
                                @if($clocks->where('user_id', $user['id'])->where('date', $now->format('Y-m-d'))->first())
                                    {{substr($clocks->where('user_id', $user['id'])->where('date', $now->format('Y-m-d'))->first()->start_time, 0, -3)}}
                                @else
                                    -
                                @endif
                            </td>
                            <td style="cursor: pointer" href="#" data-bs-toggle="modal" data-bs-target="#showUserInfo" onclick="getUserInfo('{{$user['firstname']}}', '{{$user['middlename']}}','{{$user['lastname']}}', '{{$user['email']}}','{{$user['phone_number']}}','{{$user['id']}}','{{$user['role_id']}}')">
                                @if($clocks->where('user_id', $user['id'])->where('date', $now->format('Y-m-d'))->first())
                                    {{substr($clocks->where('user_id', $user['id'])->where('date', $now->format('Y-m-d'))->first()->end_time, 0, -3)}}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
<a style="color: white; cursor: pointer" id="changeFont" target="_blank" href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">.</a>

    <script>
        $("#clock_button").on('click', function () {
            // https://sweetalert.js.org/docs/
            event.preventDefault();

            swal({
                title: "Pas Op!",
                text: "Weet u zeker dat u wilt "+ $(this).text() +"?",
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

            setInterval(function (){
                $('.swal-confirm-button').on('click', function () {
                    $('#klok-form').submit();
                    clearInterval();
                });

                $('.swal-cancel-button').on('click', function () {
                    console.log('Canceled');
                    clearInterval();
                });
            }, 500);
        });
    </script>
@endsection
