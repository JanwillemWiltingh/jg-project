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
                                <div style="text-align: center !important; font-size: 25px">
                                    <a style="float: right; font-size: 25px" href="{{route('admin.rooster.user_rooster', ['user' => request('user'), 'week' => request('week') + 1])}}"><i class="fa fa-arrow-right" ></i></a>
                                    {{$weekstring}}
                                    <a style="float: left; font-size: 25px;" href="{{route('admin.rooster.user_rooster', ['user' => request('user'), 'week' => request('week') - 1] )}}"><i class="fa fa-arrow-left" ></i></a>
                                </div>
                                <p style="text-align: center;">
                                    <a style="font-size: 15px; margin-top: -10px" href="#" data-bs-toggle="modal" data-bs-target="#disableModal">Dagen uitzetten</a>
                                </p>
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
                                                <th rowspan="{{ $days[$i]['rowspan'] }}" class="align-middle text-center" style="@if($days[$i]['from_home'] != "") background-color: lightblue; @else background-color:#f0f0f0; @endif border-radius: 5px;">

                                                    @if($days[$i]['from_home'] != "")
                                                        @if($days[$i]['from_home'] == 1)
                                                            <p style="font-weight: lighter">Thuis</p>
                                                        @else
                                                            <p style="font-weight: lighter">Op kantoor</p>
                                                        @endif
                                                    @endif

                                                    @if(!$days[$i]['comment'] == "")
                                                        {{$days[$i]['comment']}} <br>
                                                    @endif

                                                    @if($days[$i]['comment'] == "Disabled")
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#editDisableModal" style="font-weight: lighter; text-decoration: none;" onclick="modalData({{$i}})"><i class="fa fa-pencil-alt"></i></a>
                                                        <a href="{{route('admin.rooster.delete_disable_days', ['user' => request('user'), 'week' => request('week'), 'weekday' => $i + 1])}}" style="font-weight: lighter; text-decoration: none;"><i class="fa fa-trash"></i></a>
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
            function modalData(weekday)
            {
                document.getElementById('weekday').value = weekday + 1;
            }
        </script>
@endsection
