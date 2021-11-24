@extends('layouts.app')

@section('content')
{{--    @dd($calendarData)--}}
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
                <div class="card-header ">
                    <div class="card-body">
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
                            <table class="card-body table table-bordered">
                                <thead>
                                <th width="14%" style="border: none; text-align: center">Time</th>
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
                                                @if(!$days[$i]['comment'] == "Disabled")
                                                    <input type="hidden" value="{{$days[$i]['start_time']}}" id="start_time_user_rooster{{$i + 1}}">
                                                    <input type="hidden" value="{{$days[$i]['end_time']}}" id="end_time_user_rooster{{$i + 1}}">
                                                    <input type="hidden" value="{{$availability->where('id', $days[$i]['id'])->first()->start_year}}-W{{$availability->where('id', $days[$i]['id'])->first()->start_week}}" id="start_rooster{{$i + 1}}">
                                                    <input type="hidden" value="{{$availability->where('id', $days[$i]['id'])->first()->end_year}}-W{{$availability->where('id', $days[$i]['id'])->first()->end_week}}" id="end_rooster{{$i + 1}}">
                                                    <input type="hidden" value="{{$availability->where('id', $days[$i]['id'])->first()->comment}}" id="comment{{$i + 1}}">
                                                @endif
                                                <th rowspan="{{ $days[$i]['rowspan'] }}" class="align-middle text-center" style="@if($days[$i]['comment'] != "Onbereikbare dag.") background-color: #1C88A4; @else background-color:#f0f0f0; @endif color: white;">
                                                    @if($days[$i]['comment'] != "Onbereikbare dag.")
                                                        @if($days[$i]['from_home'])
                                                            <p style="font-weight: lighter">Thuis</p>
                                                        @else
                                                            <p style="font-weight: lighter">Op kantoor</p>
                                                        @endif
                                                    @endif
                                                    @if(!$days[$i]['comment'] == "")
                                                        @if($days[$i]['comment'] == "Onbereikbare dag.")
                                                            @if($days[$i]['by_admin'] == 0)
                                                                <p style="color: #000000">{{$days[$i]['comment']}}</p>
                                                                <input type="hidden" id="start_date_disable{{$i + 1}}" value="{{$days[$i]['start_time']}}">
                                                                <input type="hidden" id="end_date_disable{{$i + 1}}" value="@if(strlen(substr($days[$i]['end_time'], 6)) == 1) 0 @endif{{$days[$i]['end_time']}}">
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
                                                    @if(!$days[$i]['comment'] == "Onbereikbare dag.")
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
