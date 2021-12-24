@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12" style="margin-top: -32px !important;">

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Selectie Opties</h4>
                            <form method="GET" action="{{ route('admin.compare.index') }}">
                                <x-forms.single-select :array="$all_users" :fields="['firstname', 'middlename', 'lastname']" value="{{ $user }}" name="user" default="Alle Gebruikers" capitalize="true"></x-forms.single-select>

                                <div class="form-group">
                                    @foreach (['month' => 'Maand', 'weeks' => 'Week', 'day' => 'Dag'] as $id => $format)
                                        <div class="form-check">
                                            <input type="radio" name="date-format" id="{{ $id }}" value="{{ $id }}"
                                                @if($id == $input_field)
                                                    checked
                                                @endif>
                                            <label for="{{ $id }}">{{ $format }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Time Inputs -->
                                <div class="form-group" id="month-group" @if($input_field != 'month') style="display: none;" @endif>
                                    <label for="month">Maand</label>
                                    <input name="month" id="month" type="month" class="form-control" value="{{ old('month') ?? session('month') ?? $month }}">
                                </div>

                                <div class="form-group" id="week-group" @if($input_field != 'weeks') style="display: none;" @endif>
                                    <label for="weeks">Week</label>
                                    <input name="weeks" id="weeks" type="week" class="form-control" value="{{ old('weeks') ?? session('weeks') ?? $weeks }}">
                                </div>

                                <div class="form-group" id="day-group" @if($input_field != 'day') style="display: none;" @endif>
                                    <label for="day">Day</label>
                                    <input name="day" id="day" type="date" class="form-control" value="{{ old('day') ?? session('day') ?? $day }}">
                                </div>

                                <!-- Submit button -->
                                <button type="submit" class="btn jg-color-3" style="color: white">Selecteer</button>

{{--                                <!-- Time switch -->--}}
{{--                                <label class="switch">--}}
{{--                                    <input type="checkbox" id="time-switch" name="time_switch" @if(old('time_switch')) checked @endif>--}}
{{--                                    <span class="slider round"></span>--}}
{{--                                </label>--}}
{{--                                <div style="display: inline-block">--}}
{{--                                    <label for="switch" id="switch-label">@if(old('time_switch')) Precies @else Uren @endif</label>--}}
{{--                                </div>--}}

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover table-striped" style="box-shadow: 0 0 5px 0 lightgrey;">
                        <thead>
                        <tr>
                            <th scope="col">Naam</th>
                            <th scope="col">Tijd ingepland</th>
                            <th scope="col">Tijd gewerkt</th>
                            <th scope="col">Verschil</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <!-- Users full name -->
                                <td>
                                    {{ $user['firstname'] }} {{ $user['middlename'] }} {{ $user['lastname'] }}
                                </td>
                                {{-- tijd ingepland --}}

                                <!-- Time Work planned in a week and month -->
                                @if($input_field == 'day')
                                    <td class="uren" style="width: 22.5%">
                                        {{ $user->plannedWorkADayInHours(Carbon\Carbon::parse($day)->format('Y'), Carbon\Carbon::parse($day)->weekOfYear, Carbon\Carbon::parse($day)->format('d')) }}
                                    </td>
                                @elseif($input_field == 'weeks')
                                    <td class="uren" style="width: 22.5%">
                                        {{ $user->plannedWorkAWeekInHours(date('Y'), str_replace('W', '',explode('-', $weeks)[1]), $user->id ) }} uur
                                    </td>
                                @else
                                    <td class="uren" style="width: 22.5%">
                                        {{ $user->plannedWorkAMonthInHours(date('Y'), explode('-', $month)[1]) }} uur
                                    </td>
                                @endif
                                <td class="uren" style="width: 22.5%">
                                    @if($input_field == 'day')
                                        {{ $user->workedInADayInHours(Carbon\Carbon::parse($day)->format('Y'), Carbon\Carbon::parse($day)->format('m'), Carbon\Carbon::parse($day)->format('d')) }}
                                    @elseif($input_field == 'weeks')
                                        {{ $user->workedInAWeekInHours(str_replace('W', '',explode('-', $weeks)[1])) }} uur
                                    @else
                                        {{ $user->WorkedInAMonthInHours(explode('-', $month)[1]) }} uur
                                    @endif
                                </td>
                                <!-- Vergelijken uren -->
                                @if($input_field == 'day')
                                    <td class="{{ $user->fieldColorForDay(Carbon\Carbon::parse($day)->format('Y'), Carbon\Carbon::parse($day)->format('m'), Carbon\Carbon::parse($day)->format('d')) }} uren" style="width: 22.5%">
                                        {{ $user->compareDayWorkedInHours(Carbon\Carbon::parse($day)->format('Y'), Carbon\Carbon::parse($day)->format('m'), Carbon\Carbon::parse($day)->format('d')) }}
                                    </td>
                                @elseif($input_field == 'weeks')
                                    <td class="{{ $user->fieldColorForWeek(date('Y'), $weeks) }} uren" style="width: 22.5%">
                                        {{ abs($user->compareWeekWorkedInHours(date('Y'), str_replace('W', '',explode('-', $weeks)[1])), $user->id) }} uur
                                    </td>
                                @else
                                    <td class="{{ $user->fieldColorForMonth(date('Y'), $month) }} uren" style="width: 22.5%">
                                        {{ abs($user->compareMonthWorkedInHours(date('Y'), explode('-', $month)[1])) }} uur
                                    </td>
                                @endif

                                <!-- Show buttons -->
                                <td style="width: 5px">
                                    @if($input_field == 'day')
                                        <a href="{{ route('admin.compare.show', ['user' => $user['id'], 'type' => $input_field, 'time' => $day]) }}" title="Uren bekijken">
                                            <i class="fa fa-eye icon-color"></i>
                                        </a>
                                    @elseif($input_field == 'weeks')
                                        <a href="{{ route('admin.compare.show', ['user' => $user['id'], 'type' => $input_field, 'time' => $weeks]) }}" title="Uren bekijken">
                                            <i class="fa fa-eye icon-color"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('admin.compare.show', ['user' => $user['id'], 'type' => $input_field, 'time' => $month]) }}" title="Uren bekijken">
                                            <i class="fa fa-eye icon-color"></i>
                                        </a>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{$users->links()}}
        </div>
    </div>
@endsection
