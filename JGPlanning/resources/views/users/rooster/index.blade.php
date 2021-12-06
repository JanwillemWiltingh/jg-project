@extends('layouts.app')

@section('content')
@include('modals')

<div class="content fadeInDown">
    @if($errors->all())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
{{--                <select id="dropdown_rooster" class="form-control">--}}
{{--                    <option>Week</option>--}}
{{--                    <option>Maand</option>--}}
{{--                </select>--}}
{{--                <div class="card-header" id="calender_hide" style="display: none">--}}
{{--                    <div class="card-body">--}}
{{--                        @include('calender')--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="card-header" id="rooster">
                    <div class="card-body" @if(App\Models\Browser::isMobile()) style="width: 120% !important; right: 10%" @endif>
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                            <div style="text-align: center !important; font-size: 25px">
                                <a style="float: right; font-size: 25px" href="{{route('rooster.index', ['week' => request('week') + 1, 'year' => request('year')])}}"><i class="fa fa-arrow-right" ></i></a>
                                <a style="float: left; font-size: 25px;" href="{{route('rooster.index', ['week' => request('week') - 1, 'year' => request('year')])}}"><i class="fa fa-arrow-left" ></i></a>
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
                            <p style="
                                        text-align: center;
                                        background: -webkit-linear-gradient(#1A6686, #1C88A4);
                                        -webkit-background-clip: text;
                                        -webkit-text-fill-color: transparent;
                                        font-size: 45px;
                                        font-weight: bolder;
                                        font-style: italic;
                                        margin-top: -40px;
                                    ">
                                <a style="font-size: 15px; border-bottom: 2px solid #1A6686;" href="#" data-bs-toggle="modal" data-bs-target="#disableModal">
                                    <i class="fa fa-pencil-alt"></i>
                                    Dagen beheren
                                </a>
                            </p>
                            <form id="week_form">
                                <input type="hidden" value="{{request('week')}}" id="hidden_week">
                                <input type="hidden" value="{{request('year')}}" id="hidden_year">
                                <input type="week" class="form-control" name="week" id="week" value="{{request('year')}}-W{{request('week')}}">
                            </form>
                            <table class="table table-bordered  @if(App\Models\Browser::isMobile()) mobile-table @endif">
                                <thead>
                                @if(!App\Models\Browser::isMobile())
                                    <th width="14%" style="border: none; text-align: center">Tijd</th>
                                    @for($i = 1; $i < count($weekDays) + 1; $i++)
                                        <th width="14%" style="border: none; text-align: center;">
                                            {{ $weekDays[$i] }}
                                        </th>
                                    @endfor
                                @else
                                    <th style="border: none; text-align: center; font-size: 12px;">Tijd</th>
                                    @for($i = 1; $i < count(App\Models\Availability::WEEK_DAYS_MOB) + 1; $i++)
                                        <th style="border: none; text-align: center; font-size: 12px;">
                                            {{ App\Models\Availability::WEEK_DAYS_MOB[$i] }}
                                        </th>
                                    @endfor
                                @endif
                                </thead>
                                <tbody>
                                @foreach($calendarData as $time => $days)
                                    <tr>
                                        <td style=" @if(App\Models\Browser::isMobile()) font-size: 10px !important; @endif">
                                            {{ $time }}
                                        </td>
                                        @for($i = 0; $i < count($days); $i++)
                                            @if(is_array($days[$i]))
                                                {{--Hidden value's voor de edit functie--}}
                                                <input type="hidden" value="{{$days[$i]['start_time']}}" id="start_time_user_rooster{{$i + 1}}">
                                                <input type="hidden" value="{{$days[$i]['end_time']}}" id="end_time_user_rooster{{$i + 1}}">
                                                @if($availability->where('id', $days[$i]['id'])->first())
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

                                                    <input type="hidden" value="{{$availability->where('id', $days[$i]['id'])->first()->comment}}" id="comment{{$i + 1}}">
                                                @endif
                                                <th rowspan="{{ $days[$i]['rowspan'] }}" class="align-middle text-center" style="@if($days[$i]['comment'] != "Uitgezet door admin.") background-color: #1C88A4; @else background-color:#f0f0f0; @endif  color: white;">
                                                    @if($days[$i]['comment'] != "Uitgezet door admin.")
                                                        @if($days[$i]['from_home'])
                                                            <p style="font-weight: lighter">Thuis</p>
                                                        @else
                                                            <p style="font-weight: lighter">Op kantoor</p>
                                                        @endif
                                                    @endif
                                                    @if(!$days[$i]['comment'] == "")
                                                        @if($days[$i]['comment'] == "Uitgezet door admin.")
                                                            @if($days[$i]['by_admin'] == 0)
                                                                <p style="color: #000000">{{$days[$i]['comment']}}</p>
                                                                <input type="hidden" id="start_date_disable{{$i + 1}}" value="{{$days[$i]['start_time']}}">
                                                                <input type="hidden" id="end_date_disable{{$i + 1}}" value="{{$days[$i]['end_time']}}">
                                                                <a href="#" onclick="modalData({{$i + 1}}, {{$days[$i]['disabled_id']}})" data-bs-toggle="modal" data-bs-target="#editDisableModal" style="font-weight: lighter; text-decoration: none; color: black" id="disabled_modal_edit{{$i + 1}}"><i class="fa fa-pencil-alt"></i></a>
                                                            @else
                                                                <p style="color: black">{{$days[$i]['comment']}}</p>
                                                            @endif
                                                        @else
                                                            "{{$days[$i]['comment']}}"
                                                        @endif
                                                    @else
                                                        Geen opmerking
                                                    @endif
                                                    @if($days[$i]['comment'] != "Uitgezet door admin.")
                                                        <p style="font-weight: lighter">{{$days[$i]['start_time']}} - {{$days[$i]['end_time']}}</p> <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalEdit" style="font-weight: lighter; text-decoration: none; font-size: 15px; color: white; " onclick="modalData({{$i + 1}}, {{$days[$i]['id']}})" id="edit_rooster_modal{{$i + 1}}"><i class="fa fa-pencil-alt"></i></a>
                                                    @endif
                                                </th>
                                            @elseif ($days[$i] === 1)
                                                <td></td>
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
