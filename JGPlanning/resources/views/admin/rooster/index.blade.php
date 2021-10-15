@extends('layouts.app')

@section('content')
    @include('modals')
    <div class="content fadeInDown">
        <form id="admin-availability" type="GET">
            @csrf
            <label>
                <p>Bekijk gebruikers opgegeven beschikbaarheid:</p>
            </label>
            <select name="user" class="form-control" id="admin-availability-dropdown">
                @foreach(\App\Models\User::where('role_id', 2)->get() as $user)
                    <option value="{{$user->id}}" @if(request('user') == $user->id) selected @endif>{{$user->name}}</option>
                @endforeach
            </select>
        </form>

        @foreach ($errors->all() as $error)
            <p style="color:red;">{{ $error }}</p>
        @endforeach
        @if(session()->has('error'))
            <p style="color:red;">
                {{ session()->get('error') }}
            </p>
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
                            <div>
                                <table class="card-body table table-bordered">
                                    <thead >
                                    <th width="14%" style="border: none; text-align: center; border-radius: 15px 15px 0 0 !important;" >Time</th>
                                    @for($i = 1; $i < count($weekDays) + 1; $i++)
                                        @if(is_null(json_decode($user_info->unavailable_days)))
                                            <th width="14%" style="border: none; text-align: center;">
                                                <form method="post" id="dayForm">
                                                    @csrf
                                                    {{ $weekDays[$i] }}
                                                    <input type="hidden" id="userIdDisableDays" value="{{request('user')}}">

                                                    <input type="checkbox" id="disableDays{{$i}}" class="toggle-box" name="from_home"/>
                                                    <label for="disableDays{{$i}}" class="toggle-label" style="width: 25%; top: -25px; margin-bottom: -22px"></label>
                                                </form>
                                            </th>
                                        @else
                                            <th width="14%" style="border: none; text-align: center; @if(!is_null(json_decode($user_info->unavailable_days)[$i - 1])) background: lightgrey @endif">
                                                <form method="post" id="dayForm">
                                                    @csrf
                                                    {{ $weekDays[$i] }}
                                                    <input type="hidden" id="userIdDisableDays" value="{{request('user')}}">

                                                    <input type="checkbox" id="disableDays{{$i}}" class="toggle-box" name="from_home" @if(!is_null(json_decode($user_info->unavailable_days)[$i - 1])) checked @endif/>
                                                    <label for="disableDays{{$i}}" class="toggle-label" style="width: 25%; top: -25px; margin-bottom: -22px"></label>
                                                </form>
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
                                                @if(is_null(json_decode($user_info->unavailable_days)))
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
                                                    @if (json_decode($user_info->unavailable_days)[$i] == "on")
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
