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
                <div class="card-header ">
                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                            <div style="text-align: center !important; font-size: 25px">
                                <a style="float: right; font-size: 25px" href="{{route('rooster.index', request('week') + 1)}}"><i class="fa fa-arrow-right" ></i></a>
                                <p style="text-align: center; font-size: 25px">{{$weekstring}}</p>
                                <a style="float: left; font-size: 25px; margin-top: -53px" href="{{route('rooster.index', request('week') - 1)}}"><i class="fa fa-arrow-left" ></i></a>
                            </div>
                            <p style="text-align: center;">
                                <a style="font-size: 15px; margin-top: -10px" href="#" data-bs-toggle="modal" data-bs-target="#disableModal">Dagen uitzetten</a>
                            </p>
                            <form id="week_form">
                                <input type="hidden" value="{{request('week')}}" id="hidden_week">
                                <input type="week" class="form-control" name="week" id="week">
                            </form>
                            <table class="card-body table table-bordered">
                                <thead>
                                <th width="14%" style="border: none; text-align: center">Time</th>
                                @for($i = 1; $i < count($weekDays) + 1; $i++)
                                    @if($disabled_array)
                                        <th width="14%" style="border: none; text-align: center;">
                                            {{ $weekDays[$i] }}
                                            @if($availability)
                                                @if(!$disabled_array[$i - 1])
                                                    @if(is_null($availability->where('weekdays', $i)->where('start_week', '<=', request('week'))->where('end_week', '>=', request('week'))->first()))
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalAdd" onclick="modalData({{$i}}, {{\Illuminate\Support\Facades\Auth::user()->id}})"><i class="fa fa-plus"></i></a>
                                                    @else
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalEdit" onclick="modalData({{$i}}, {{\Illuminate\Support\Facades\Auth::user()->id}})"><i class="fa fa-pen"></i></a>
                                                        <a href="{{route('rooster.delete_rooster', ['user' => $user, 'weekday' =>$i, 'week' => request('week')])}}"><i class="fa fa-trash"></i></a>
                                                    @endif
                                                @endif
                                            @else
                                                @if(!$disabled_array[$i - 1])
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalAdd" onclick="modalData({{$i}}, {{\Illuminate\Support\Facades\Auth::user()->id}})"><i class="fa fa-plus"></i></a>
                                                @endif
                                            @endif
                                        </th>
                                    @else
                                        <th width="14%" style="border: none; text-align: center; @if(!is_null(json_decode($user_info->unavailable_days)[$i - 1])) background: lightgrey @endif">
                                            {{ $weekDays[$i] }}
                                            @if($availability)
                                                @if(is_null($availability->where('weekdays', $i)->where('start_week', '<=', request('week'))->where('end_week', '>=', request('week'))->first()))
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalAdd" onclick="modalData({{$i}}, {{\Illuminate\Support\Facades\Auth::user()->id}})"><i class="fa fa-plus"></i></a>
                                                @else
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalEdit" onclick="modalData({{$i}}, {{\Illuminate\Support\Facades\Auth::user()->id}})"><i class="fa fa-pen"></i></a>
                                                    <a href="{{route('delete_rooster', ['user' => $user, 'weekday' =>$i])}}"><i class="fa fa-trash"></i></a>
                                                @endif
                                            @else
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalAdd" onclick="modalData({{$i}}, {{\Illuminate\Support\Facades\Auth::user()->id}})"><i class="fa fa-plus"></i></a>
                                            @endif
                                        </th>
                                    @endif
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
                                                <th rowspan="{{ $days[$i]['rowspan'] }}" class="align-middle text-center" style="@if($days[$i]['start_time'] != "") background-color: #1C88A4; @else background-color:#f0f0f0; @endif color: white;">
                                                    @if($days[$i]['start_time'] != "")
                                                        @if($days[$i]['from_home'])
                                                            <p style="font-weight: lighter">Thuis</p>
                                                        @else
                                                            <p style="font-weight: lighter">Op kantoor</p>
                                                        @endif
                                                    @endif
                                                    @if(!$days[$i]['comment'] == "")
                                                        @if($days[$i]['comment'] == "Disabled")
                                                            @if($days[$i]['by_admin'] == 0)
                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#editDisableModal" style="font-weight: lighter; text-decoration: none;" onclick="modalData({{$i}})"><i class="fa fa-pencil-alt"></i></a>
                                                                <a href="{{route('rooster.delete_disable', ['week' => request('week'), 'weekday' => $i + 1])}}" style="font-weight: lighter; text-decoration: none;"><i class="fa fa-trash"></i></a>
                                                                <p style="color: black">{{$days[$i]['comment']}}</p>
                                                            @else
                                                                <p style="color: black">{{$days[$i]['comment']}}</p>
                                                            @endif
                                                        @else
                                                            "{{$days[$i]['comment']}}"
                                                        @endif
                                                    @else
                                                        Geen opmerking
                                                    @endif

                                                    @if($days[$i]['start_time'] != "")
                                                        <p style="font-weight: lighter">{{$days[$i]['start_time']}} - {{$days[$i]['end_time']}}</p>
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
    </div>

    <script>
        function modalData(weekday, id)
        {
            document.getElementById('is_rooster').value = true;
            document.getElementById('is_rooster_edit').value = true;
            document.getElementById('weekday').value = weekday;
            document.getElementById('weekday_edit').value = weekday;
        }
    </script>
@endsection
