@extends('layouts.app')

@section('content')
    @foreach ($errors->all() as $error)
        <p style="color:red;">{{ $error }}</p>
    @endforeach
    @if(session()->has('error'))
        <p style="color:red;">
            {{ session()->get('error') }}
        </p>
    @endif

    <div class="modal fade" id="availabilityModalAdd" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="a">Add Availability</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{route('availability')}}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="weekday" id="weekday">
                        <input type="hidden" name="user_id" id="user_id">
                        <label style="width: 49%">
                            <p>Start time:</p>
                            <input type="time" name="start_time" class="form-control" style="outline: none;" id="time_picker_av_start"  min="08:00" max="18:00">
                        </label>
                        <label style="width: 49%">
                            <p>End Time:</p>
                            <input type="time" name="end_time" class="form-control" style="outline: none;" id="time_picker_av_start" min="08:00" max="18:00">
                        </label>
                        <p style="font-size: 12px" class="text-warning">De tijden die u invult worden op halve uren en hele uren afgerond</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary nav-colo">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="availabilityModalEdit" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="a">Edit Availability</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{route('edit_availability')}}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="weekday" id="weekday_edit">
                        <input type="hidden" name="user_id" id="user_id_edit">
                        <label style="width: 49%">
                            <p>Start time:</p>
                            <input type="time" name="start_time" class="form-control" style="outline: none;" id="time_picker_av_start"  min="08:00" max="18:00">
                        </label>
                        <label style="width: 49%">
                            <p>End Time:</p>
                            <input type="time" name="end_time" class="form-control" style="outline: none;" id="time_picker_av_start" min="08:00" max="18:00">
                        </label>
                        <p style="font-size: 12px" class="text-warning">De tijden die u invult worden op halve uren en hele uren afgerond</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary nav-colo">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="content" style="width: 315% !important;">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table class="card-body table table-bordered">
                            <thead>
                            <th width="125" style="border: none;">Time</th>
                            @for($i = 1; $i < count($weekDays) + 1; $i++)
                                <th width="13%" style="border: none; text-align: center">
                                    {{ $weekDays[$i] }} <br>
                                    @if(is_null($availability->where('weekdays', $i)->first()))
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalAdd" onclick="modalData({{$i}}, {{\Illuminate\Support\Facades\Auth::user()->id}})"><i class="fa fa-plus"></i></a>
                                    @else
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModalEdit" onclick="modalData({{$i}}, {{\Illuminate\Support\Facades\Auth::user()->id}})"><i class="fa fa-pen"></i></a>
                                    @endif
                                    <a href="{{route('delete_availability', ['user' => \Illuminate\Support\Facades\Auth::user()->id, 'weekday' =>$i])}}"><i class="fa fa-trash"></i></a>
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
                                                boy what the boy
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

    <script>
        function modalData(weekday, id)
        {
            document.getElementById('weekday').value = weekday;
            document.getElementById('user_id').value = id;
            document.getElementById('weekday_edit').value = weekday;
            document.getElementById('user_id_edit').value = id;
        }
    </script>
@endsection
