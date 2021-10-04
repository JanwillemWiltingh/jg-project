@extends('layouts.app')

@section('content')
{{--    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#availabilityModal">--}}
{{--        Launch demo modal--}}
{{--    </button>--}}
{{--    --}}
    <a href="{{route('availability')}}"><i class="fa fa-plus"></i> Basic 9/5 schedule</a> <br>
    <a href="#" data-bs-toggle="modal" data-bs-target="#availabilityModal"><i class="fa fa-pen"></i> Edit a day</a> <br>

@foreach ($errors->all() as $error)
    <p style="color:red;">{{ $error }}</p>
@endforeach

    <div class="modal fade" id="availabilityModal" tabindex="-1" role="dialog" aria-labelledby="a" aria-hidden="true">
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
                        <label>
                            <p>Start time:</p>
                            <input type="time" name="start_time" class="form-control" style="outline: none;" id="time_picker_av_start"  min="08:00" max="18:00">
                        </label>
                        <label>
                            <p>End Time:</p>
                            <input type="time" name="end_time" class="form-control" style="outline: none;" id="time_picker_av_start" min="08:00" max="18:00">
                        </label>
                        <label>
                            <p>Weekday:</p>
                            <select class="form-control dropdown-planning" style="width: 170%; height: 70% !important;" name="weekdays">
                                @for($i = 1; $i < count($weekDays) + 1; $i++)
                                    <option value="{{$i}}">{{$weekDays[$i]}}</option>
                                @endfor
                            </select>
                        </label>
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
                        <table class="table table-bordered">
                            <thead>
                                <th width="125">Time</th>
                                @foreach($weekDays as $day)
                                    <th width="13%">{{ $day }}</th>
                                @endforeach
                            </thead>
                            <tbody>
                            @foreach($calendarData as $time => $days)
                                <tr>
                                    <td>
                                        {{ $time }}
                                    </td>
                                    @foreach($days as $value)
                                        @if (is_array($value))
                                            <td rowspan="{{ $value['rowspan'] }}" class="align-middle text-center" style="background-color:#f0f0f0">
                                                boy what the boy
                                            </td>
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
@endsection
