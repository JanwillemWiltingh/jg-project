@extends('layouts.app')

@section('content')

@include('modals')
    <div class="content fadeInDown">
        @if($errors->all())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
                <div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            </div>
        @endif
        <form id="admin-availability" type="GET">
            @csrf
            <label>
                <p>Bekijk gebruikers opgegeven beschikbaarheid:</p>
            </label>
            <input type="hidden" value="{{request('week')}}" id="request_week">
            <input type="hidden" value="{{request('year')}}" id="request_year">
            <select name="user" class="form-control" id="admin-availability-dropdown">
                @foreach(\App\Models\User::get() as $user)
                    <option value="{{$user['id']}}" @if(request('user') == $user['id']) selected @endif>{{$user['firstname']}} @if(!empty($user['middlename'])) {{$user['middlename']}}  @endif{{$user['lastname']}}</option>
                @endforeach
            </select>
        </form>
        <div class="loader d-none" id="loader"></div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header" id="calender_hide">
                        <div class="card-body">
                            <button class="btn jg-color-1" style="
                                color: white !important;
                                float: right;
                                top: 60px;
                                right: 28px;
                            ">Maand</button>
                            <button class="btn jg-color-1 " style="
                                color: black !important;
                                float: right;
                                top: 60px;
                                right: 28px;
                                background: lightgray !important;
                                border-color: lightgray !important;
                            " id="week_rooster">
                                Week
                            </button>
                            <div style=" position:absolute;left: 72.4% !important; top: 13% !important;">
                                <a href="{{route('admin.rooster.user_rooster', ['user' => request('user') ,'week' => request('week'), 'year' => date('Y') - 1])}}" class="btn jg-color-1" style="color: white; @if(request('year') == date('Y') - 1) background: lightgray !important; border-color: lightgray !important; color: black !important; @endif">Vorig jaar</a>
                                <a href="{{route('admin.rooster.user_rooster', ['user' => request('user') ,'week' => request('week'), 'year' => date('Y')])}}" class="btn jg-color-1" style="color: white; @if(request('year') == date('Y')) background: lightgray !important; border-color: lightgray !important; color: black !important; @endif">Dit jaar</a>
                                <a href="{{route('admin.rooster.user_rooster', ['user' => request('user') ,'week' => request('week'), 'year' => date('Y') + 1])}}" class="btn jg-color-1" style="color: white; @if(request('year') == date('Y') + 1) background: lightgray !important; border-color: lightgray !important; color: black !important; @endif" >Volgend jaar</a>
                            </div>
                            @include('calender')
                            <strong>
                                @if(!$user_info->checkIfRoosterIsSolidified(\Carbon\Carbon::parse(date('Y-m-d'))->addWeek()))
                                    <button id="solidify_next_week" style="float: right !important; bottom: 65px; right: 60px" class="btn btn-primary jg-color-3 border-0" href="" data-toggle="tooltip">Zet rooster vast</button>
                                @else
                                    <button id="un_solidify_next_week_this" style="float: right !important; bottom: 65px; right: 60px" class="btn btn-primary jg-color-3 border-0" href="" data-toggle="tooltip">Rooster week {{\Carbon\Carbon::now()->weekOfYear}} bewereken</button>
                                    <button id="un_solidify_next_week_next" style="float: right !important; bottom: 25px; right: -164px" class="btn btn-primary jg-color-3 border-0" href="" data-toggle="tooltip">Rooster week {{\Carbon\Carbon::now()->addWeek()->weekOfYear}} bewereken</button>
                                @endif
                            </strong>

                            <input type="hidden" id="admin_user_id_edit" value="{{request('user')}}">
                        </div>
                    </div>
                    <div class="card-header" id="rooster" style="display: none">
                        <div class="card-body">
                            @if(session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                                <div style="text-align: center !important; font-size: 25px">
                                    <a style="float: right; font-size: 25px" href="{{route('admin.rooster.user_rooster', ['user' => request('user'), 'week' => request('week') + 1, 'year' => request('year')])}}"><i class="fa fa-arrow-right" ></i></a>
                                    <a style="float: left; font-size: 25px;" href="{{route('admin.rooster.user_rooster', ['user' => request('user'), 'week' => request('week') - 1, 'year' => request('year')])}}"><i class="fa fa-arrow-left" ></i></a>
                                </div>
                                <div class="dashboard-welkom-rooster" style="text-align: center !important">
                                    <h1 style="font-size: 25px">
                                        {{$weekstring}}
                                    </h1>
                                    <br>
                                    <a style="font-size: 20px">
                                        {{request('year')}}
                                    </a>
                                </div>
                                <button class="btn jg-color-1" style="
                                        color: black !important;
                                        float: right;
                                        right: 28px;
                                        bottom: 44px;
                                        background: lightgray !important;
                                        border-color: lightgray !important;
                                    " id="maand">
                                    Maand
                                </button>
                                <button class="btn jg-color-1" style="
                                        color: white !important;
                                        float: right;
                                        right: 28px;
                                        bottom: 44px;
                                    ">Week</button>
                                <form id="week_form">
                                    <input type="hidden" value="{{request('week')}}" id="hidden_week">
                                    <input type="hidden" value="{{request('year')}}" id="hidden_year">
                                    <input type="week" class="form-control" name="week" id="week" value="{{request('year')}}-W{{request('week')}}">
                                </form>
                            <table class="card-body table table-bordered">
                                <thead >
                                <th width="14%" style="border: none; text-align: center; border-radius: 15px 15px 0 0 !important;" >Time</th>
                                @for($i = 1; $i < count($weekDays) + 1; $i++)
                                    <th width="14%" style="border: none; text-align: center;">
                                        {{ $weekDays[$i] }}
                                    </th>
                                @endfor
                                </thead>

                                <tbody>
                                @foreach($calendarData as $time => $days)
                                    <tr>
                                        <td>
                                            {{ $time }}
                                        </td>
                                        @for($i = 0; $i < count($days); $i++)
                                            @if(is_array($days[$i]))
                                                {{-- Hidden value's voor de edit functie--}}
                                                    <input type="hidden" value="{{$days[$i]['start_time']}}" id="start_time_user_rooster{{$i + 1}}">
                                                    <input type="hidden" value="{{$days[$i]['end_time']}}" id="end_time_user_rooster{{$i + 1}}">
                                                @if($availability->where('id', $days[$i]['id'])->first())
                                                    <input type="hidden" value="{{$days[$i]['from_home']}}" id="from_home{{$i + 1}}">

                                                    @if(strlen($availability->where('id', $days[$i]['id'])->first()->start_week) == 2)
                                                        <input type="hidden" value="{{$availability->where('id', $days[$i]['id'])->first()->start_year}}-W{{$availability->where('id', $days[$i]['id'])->first()->start_week}}" id="start_rooster{{$i + 1}}">
                                                    @else
                                                        <input type="hidden" value="{{$availability->where('id', $days[$i]['id'])->first()->start_year}}-W0{{$availability->where('id', $days[$i]['id'])->first()->start_week}}" id="start_rooster{{$i + 1}}">
                                                    @endif

                                                    @if(strlen($availability->where('id', $days[$i]['id'])->first()->end_week) == 2)
                                                        <input type="hidden" value="{{$availability->where('id', $days[$i]['id'])->first()->end_year}}-W{{$availability->where('id', $days[$i]['id'])->first()->end_week}}" id="end_rooster{{$i + 1}}">
                                                    @else
                                                        <input type="hidden" value="{{$availability->where('id', $days[$i]['id'])->first()->end_year}}-W0{{$availability->where('id', $days[$i]['id'])->first()->end_week}}" id="end_rooster{{$i + 1}}">
                                                    @endif
                                                @endif
                                                <th rowspan="{{ $days[$i]['rowspan'] }}" class="align-middle text-center" style="@if($days[$i]['comment'] != "Dag uitgezet.") background-color: #1C88A4; @else background-color:#f0f0f0; @endif color: white;">

                                                    @if($days[$i]['from_home'] != "")
                                                        @if($days[$i]['from_home'] == 1)
                                                            <p style="font-weight: lighter">Thuis</p>
                                                        @else
                                                            <p style="font-weight: lighter">Op kantoor</p>
                                                        @endif
                                                    @endif

                                                    @if(!$days[$i]['comment'] == "")
                                                        @if($days[$i]['comment'] == "Dag uitgezet.")
                                                            @if($days[$i]['by_admin'] == 0)
                                                                <p style="color: #000000">{{$days[$i]['comment']}}</p>
                                                                <input type="hidden" id="start_date_disable{{$i + 1}}" value="{{$days[$i]['start_time']}}">
                                                                <input type="hidden" id="end_date_disable{{$i + 1}}" value="{{$days[$i]['end_time']}}">
                                                            @else
                                                                <p style="color: black">{{$days[$i]['comment']}}</p>
                                                            @endif
                                                        @else
                                                            "{{$days[$i]['comment']}}"
                                                        @endif
                                                    @else
                                                        Geen opmerking
                                                    @endif

                                                    @if($days[$i]['comment'] != "Dag uitgezet.")
                                                        <p style="font-weight: lighter">{{$days[$i]['start_time']}} - {{$days[$i]['end_time']}}</p><a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalEdit" style="font-weight: lighter; text-decoration: none; font-size: 15px; color: white; " onclick="modalData({{$i + 1}}, {{$days[$i]['id']}})" id="edit_rooster_modal{{$i + 1}}"><i class="fa fa-pencil-alt"></i></a>
                                                    @endif
                                                </th>
                                            @elseif ($days[$i] === 1)
                                                <td></td>
                                            @elseif ($days[$i] === 0)

                                            @endif
                                        @endfor
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function modalData(weekday, id)
            {
                document.getElementById('is_rooster_edit').value = weekday;
                document.getElementById('rooster_id').value = id;
                document.getElementById('rooster_id2').value = id;
            }
        </script>
@endsection
