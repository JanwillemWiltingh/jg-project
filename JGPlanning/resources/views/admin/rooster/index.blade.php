@extends('layouts.app')

@section('content')

    @include('modals')

    @if(session()->get('message'))
        <div class="alert alert-{{ session()->get('message')['type'] }} alert-dismissible fade show" role="alert">
            {{ session()->get('message')['message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif
    <div class="content fadeInDown">
        <form id="admin-availability" type="GET">
            @csrf
            <label>
                <p>Bekijk gebruikers opgegeven beschikbaarheid:</p>
            </label>
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
                    <div class="card-header ">
                        <div class="card-body">
                            @if(session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                                <div>
                                    <a style="float: right; font-size: 25px" href="{{route('admin.rooster.user_rooster', ['user' => request('user'), 'week' => request('week') + 1])}}"><i class="fa fa-arrow-right" ></i></a>
                                    <p style="text-align: center; font-size: 25px">
                                        {{$weekstring}}
                                    </p>
                                    <a style="float: left; font-size: 25px; margin-top: -53px" href="{{route('admin.rooster.user_rooster', ['user' => request('user'), 'week' => request('week') - 1] )}}"><i class="fa fa-arrow-left" ></i></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#disableModal"><p  style="text-align: center; font-size: 15px; margin-top: -10px">Dagen uitzetten</p></a>
                                </div>
                            <table class="card-body table table-bordered">
                                <thead >
                                <th width="14%" style="border: none; text-align: center; border-radius: 15px 15px 0 0 !important;" >Time</th>
                                @for($i = 1; $i < count($weekDays) + 1; $i++)
                                    <th width="14%" style="border: none; text-align: center;">
                                        <form method="post" id="dayForm">
                                            @csrf
                                            {{ $weekDays[$i] }}
{{--                                                <input type="hidden" id="userIdDisableDays" value="{{request('user')}}">--}}

{{--                                                <input type="checkbox" id="disableDays{{$i}}" class="toggle-box" name="from_home" @if($user_info->unavailable_days and !is_null(json_decode($user_info->unavailable_days)[$i - 1])) checked @endif/>--}}
{{--                                                <label for="disableDays{{$i}}" class="toggle-label" style="width: 25%; top: -25px; margin-bottom: -22px"></label>--}}
                                        </form>
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
                                                <th rowspan="{{ $days[$i]['rowspan'] }}" class="align-middle text-center" style="@if($days[$i]['from_home'] != "") background-color: lightblue; @else background-color:#f0f0f0; @endif border-radius: 5px;">

                                                    @if($days[$i]['from_home'] != "")
                                                        @if($days[$i]['from_home'] == 1)
                                                            <p style="font-weight: lighter">Thuis</p>
                                                        @else
                                                            <p style="font-weight: lighter">Op kantoor</p>
                                                        @endif
                                                    @endif

                                                    @if(!$days[$i]['comment'] == "")
                                                        {{$days[$i]['comment']}}
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
