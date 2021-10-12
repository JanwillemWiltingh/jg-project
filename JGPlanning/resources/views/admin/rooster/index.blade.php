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
                                    <thead>
                                    <th width="125" style="border: none; text-align: center">Time</th>
                                    @for($i = 1; $i < count($weekDays) + 1; $i++)
                                        <th width="13%" style="border: none; text-align: center">
                                            {{ $weekDays[$i] }}
                                            @if(is_null($availability->where('weekdays', $i)->first()))
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalAdd" onclick="modalData({{$i}}, {{\Illuminate\Support\Facades\Auth::user()->id}})"><i class="fa fa-plus"></i></a>
                                            @else
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalEdit" onclick="modalData({{$i}}, {{\Illuminate\Support\Facades\Auth::user()->id}})"><i class="fa fa-pen"></i></a>
                                                <a href="{{route('delete_rooster', ['user' => $user, 'weekday' =>$i])}}"><i class="fa fa-trash"></i></a>
                                            @endif
                                        </th>
                                    @endfor
                                    </thead>
                                    <tbody>
                                    @foreach($calendarData as $time => $days)
                                        <tr>
                                            <td>
                                                {{ $time }}
                                            </td>
                                            @foreach($days as $value)
                                                @if (is_array($value))
                                                    <th rowspan="{{ $value['rowspan'] }}" class="align-middle text-center" style="background-color:#f0f0f0">
                                                        @if($value['from_home'] == 1)
                                                            <p style="font-weight: lighter">Thuis</p>
                                                        @else
                                                            <p style="font-weight: lighter">Op kantoor</p>
                                                        @endif

                                                        @if(!$value['comment'] == "")
                                                            "{{$value['comment']}}"
                                                        @endif

                                                        <p style="font-weight: lighter">{{$value['start_time']}} - {{$value['end_time']}}</p>
                                                    </th>
                                                @elseif ($value === 1)
                                                    <td></td>
                                                @endif
                                            @endforeach
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
