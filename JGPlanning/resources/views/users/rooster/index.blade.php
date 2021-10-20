@extends('layouts.app')

@section('content')
    @include('modals')

<div class="content fadeInDown">

@if(session()->get('message'))
    <div class="alert alert-{{ session()->get('message')['type'] }} alert-dismissible fade show" role="alert">
        {{ session()->get('message')['message'] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
    </div>
@endif
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header ">
                    <div class="card-body">
                        <div>
                            <table class="card-body table table-bordered">
                                <thead>
                                <th width="14%" style="border: none; text-align: center">Tijden</th>
                                @for($i = 1; $i < count($weekDays) + 1; $i++)
                                    @if(!is_null(json_decode($user['unavailable_days'])))
                                        <th width="14%" style="border: none; text-align: center;">
                                            {{ $weekDays[$i] }}
                                            @if(is_null(json_decode($user['unavailable_days'])[$i - 1]))
                                                @if(is_null($availability->where('weekdays', $i)->first()))
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalAdd" onclick="modalData({{$i}}, {{$user['id']}})"><i class="fa fa-plus"></i></a>
                                                @else
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalEdit" onclick="modalData({{$i}}, {{$user['id']}})"><i class="fa fa-pen"></i></a>
                                                    <a href="{{route('delete_rooster', ['user' => $user, 'weekday' =>$i])}}"><i class="fa fa-trash"></i></a>
                                                @endif
                                            @endif
                                        </th>
                                    @else
                                        <th width="14%" style="border: none; text-align: center; @if(!is_null(json_decode($user['unavailable_days'])[$i - 1])) background: lightgrey @endif">
                                            {{ $weekDays[$i] }}
                                            @if(is_null($availability->where('weekdays', $i)->first()))
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalAdd" onclick="modalData({{$i}}, {{$user['id']}})"><i class="fa fa-plus"></i></a>
                                            @else
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalEdit" onclick="modalData({{$i}}, {{$user['id']}})"><i class="fa fa-pen"></i></a>
                                                <a href="{{route('delete_availability', ['user' => $user, 'weekday' => $i])}}"><i class="fa fa-trash"></i></a>
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
                                            @if(is_null(json_decode($user['unavailable_days'])))
                                                @if(is_array($days[$i]))
                                                    <th rowspan="{{ $days[$i]['rowspan'] }}" class="align-middle text-center" style="background-color:#f0f0f0">
                                                        @if($days[$i]['from_home'] == 1)
                                                            <p style="font-weight: lighter">Thuis</p>
                                                        @else
                                                            <p style="font-weight: lighter">Op kantoor</p>
                                                        @endif

                                                        @if(!$days[$i]['comment'] == "")
                                                            "{{$days[$i]['comment']}}"
                                                        @endif

                                                        <p style="font-weight: lighter">{{$days[$i]['start_time']}} - {{$days[$i]['end_time']}}</p>
                                                    </th>
                                                @elseif ($days[$i] === 1)
                                                    <td></td>
                                                @endif
                                            @else
                                                @if (json_decode($user['unavailable_days'])[$i] == "on")
                                                    <td style="background: lightgray; border-bottom: none !important;"></td>
                                                @elseif(is_array($days[$i]))
                                                    <th rowspan="{{ $days[$i]['rowspan'] }}" class="align-middle text-center" style="background-color:#f0f0f0">
                                                        @if($days[$i]['from_home'] == 1)
                                                            <p style="font-weight: lighter">Thuis</p>
                                                        @else
                                                            <p style="font-weight: lighter">Op kantoor</p>
                                                        @endif

                                                        @if(!$days[$i]['comment'] == "")
                                                            "{{$days[$i]['comment']}}"
                                                        @endif

                                                        <p style="font-weight: lighter">{{$days[$i]['start_time']}} - {{$days[$i]['end_time']}}</p>
                                                    </th>
                                                @elseif ($days[$i] === 1)
                                                    <td></td>
                                                @endif
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
