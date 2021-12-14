@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12" style="margin-top: -32px !important;">

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Selectie opties</h4>
                            <form method="GET" action="{{ route('user.clock.index') }}">
                                <div class="form-group">
                                    @foreach (['month' => 'Maand', 'weeks' => 'Week', 'days' => 'Dag'] as $id => $format)
                                        <div class="form-check">
                                            <input type="radio" name="date-format" id="{{ $id }}" value="{{ $id }}"
                                                   @if($id == $input)
                                                   checked
                                                @endif>
                                            <label for="{{ $id }}">{{ $format }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="form-group" id="month-group" @if($input != 'month') style="display: none;" @endif>
                                    <label for="month">Maand</label>
                                    <input name="month" id="month" type="month" class="form-control" value="{{ old('month') ?? session('month') ?? $month }}">
                                </div>

                                <div class="form-group" id="week-group" @if($input != 'weeks') style="display: none;" @endif>
                                    <label for="weeks">Week</label>
                                    <input name="weeks" id="weeks" type="week" class="form-control" value="{{ old('weeks') ?? session('weeks') ?? $weeks }}">
                                </div>

                                <div class="form-group" id="day-group" @if($input != 'days') style="display: none;" @endif>
                                    <label for="day">Dag</label>
                                    <input name="day" id="day" type="date" class="form-control" value="{{ old('days') ?? session('day') ?? $day }}">
                                </div>

                                <button type="submit" class="btn btn-primary jg-color-3 border-0">Selecteer</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover" style="box-shadow: 0 0 5px 0 lightgrey;">
                        <thead>
                        <tr>
                            <th scope="col">Datum</th>
                            <th scope="col">Dag</th>
                            <th scope="col">Start tijd</th>
                            <th scope="col">Eind tijd</th>
                            <th scope="col">Gewerkte uren</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        </thead>
                        <tbody>
                        @if($days->count() > 0)
                            @foreach($days as $work_day)
                                <tr>
                                    <td style="width: 15%">{{ $work_day->format('d-m-Y') }}</td>
                                    <td style="width: 13%">{{ App\Models\Availability::WEEK_DAYS[$work_day->dayOfWeek] }}</td>
                                    <td style="width: 10%">{{ $user->getStartTime($work_day) }}</td>
                                    <td style="width: 10%">{{ $user->getEndTime($work_day) }}</td>
                                    <td style="width: 10%">{{ $user->workedInADayInHours($work_day->year, $work_day->month, $work_day->day) }}</td>
                                    <td style="width: 1%"><a class="table-label" title="Hier zal comment info komen te staan"><i class="fa fa-eye icon-color"></i></a></td>

                                <tr><!-- TODO: Add show to seen comments -->
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">
                                    @if($input == 'days')
                                        Je hebt vandaag nog niet ingeklokt
                                    @elseif($input == 'weeks')
                                        Je hebt deze week nog niet ingeklokt
                                    @else
                                        Je hebt deze maand nog niet ingeklokt
                                    @endif
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
{{--            <div class="row">--}}
{{--                <div class="col-md-12">--}}
{{--                    <table class="table table-hover" style="box-shadow: 0 0 5px 0 lightgrey;">--}}
{{--                        <thead>--}}
{{--                            <tr>--}}
{{--                                <th scope="col">#</th>--}}
{{--                                <th scope="col">Datum</th>--}}
{{--                                <th scope="col">Dag</th>--}}
{{--                                <th scope="col">Start</th>--}}
{{--                                <th scope="col">Eind</th>--}}
{{--                                <th scope="col" width="145">Tijd gewerkt</th>--}}
{{--                                <th scope="col" width="145">Tijd met pauze</th>--}}
{{--                            </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody><!-- TODO: Dit verbeteren :p -->--}}
{{--                            @if($entries->count() > 0)--}}
{{--                                @foreach($entries as $entry)--}}
{{--                                    <tr @if($loop->index % 2 == 0) class="table-light" @endif>--}}
{{--                                        <th scope="row">{{ $loop->index + 1 }}</th>--}}
{{--                                        <td>{{ $entry['date'] }}</td>--}}
{{--                                        <td>{{ App\Models\Availability::WEEK_DAYS[$entry['day']] }}</td>--}}
{{--                                        <td>{{ $entry['start_time'] }}</td>--}}
{{--                                        <td>{{ $entry['end_time'] }}</td>--}}
{{--                                        @if(number_format($entry['time'] / 3600, 1) - .5 < 0)--}}
{{--                                            <td>0.0</td>--}}
{{--                                        @else--}}
{{--                                            <td>{{ number_format($entry['time'] / 3600, 1) - .5 }}</td>--}}
{{--                                        @endif--}}
{{--                                        <td>{{ number_format($entry['time'] / 3600, 1) }}</td>--}}
{{--                                    <tr>--}}
{{--                                @endforeach--}}
{{--                            @else--}}
{{--                                <tr>--}}
{{--                                    <td colspan="7">--}}
{{--                                        @if($input == 'days')--}}
{{--                                            Je hebt vandaag nog niet ingeklokt--}}
{{--                                        @elseif($input == 'weeks')--}}
{{--                                            Je hebt deze week nog niet ingeklokt--}}
{{--                                        @else--}}
{{--                                            Je hebt deze maand nog niet ingeklokt--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            @endif--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
            {{ $entries->appends(['date-format' => $input, 'month' => $month, 'weeks' => $weeks, 'day' => $day])->links() }}
        </div>
    </div>
@endsection
