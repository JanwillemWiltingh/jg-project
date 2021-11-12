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
                                <x-forms.single-select :array="$all_users" :fields="['firstname', 'middlename', 'lastname']" name="user" default="Alle Gebruikers" capitalize="true"></x-forms.single-select>

                                <div class="form-group">
                                    @foreach (['month' => 'Maand', 'weeks' => 'Weeks'] as $id => $format)
                                        <div class="form-check">
                                            <input type="radio" name="date-format" id="{{ $id }}" value="{{ $id }}"
                                                @if($id == $input_field)
                                                    checked
                                                @endif>
                                            <label for="{{ $id }}">{{ $format }}</label>
                                        </div>
                                    @endforeach
                                </div>



                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                        <div style="display: inline-block">
                            <label for="switch">Week</label>
                        </div>

                        <div class="form-group" id="month-group" @if($input_field != 'month') style="display: none;" @endif>
                            <label for="month">Maand</label>
                            <input name="month" id="month" type="month" class="form-control" value="{{ old('month') ?? session('month') ?? $month }}">
                        </div>


                        <div class="form-group" id="week-group" @if($input_field != 'weeks') style="display: none;" @endif>
                            <label for="weeks">Week</label>
                            <input name="weeks" id="weeks" type="week" class="form-control" value="{{ old('weeks') ?? session('weeks') ?? $weeks }}">
                        </div>

                                <button type="submit" class="btn btn-primary">Selecteer</button>
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
                            <th scope="col">#</th>
                            <th scope="col">Naam</th>
                            <th scope="col">Tijd Gewerkt</th>
{{--                            <th scope="col"></th>--}}
                            <th scope="col">Tijd Ingepland</th>
{{--                            <th scope="col"></th>--}}
                            <th scope="col">Verschil</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr @if($loop->index % 2 == 0) class="table-light" @endif>
                                <th scope="row">{{ $loop->index }}</th>
{{--                                <td class="thick-table-border">--}}
                                <td>
                                    {{ $user['firstname'] }} {{ $user['middlename'] }} {{ $user['lastname'] }}
                                </td>
{{--                                <td>--}}
{{--                                    @if($input_field == 'weeks')--}}
{{--                                        {{ $user->workedInAWeekForHumans(str_replace('W', '',explode('-', $weeks)[1])) }}--}}
{{--                                    @else--}}
{{--                                        {{ $user->workedInAMonthForHumans(explode('-', $month)[1]) }}--}}
{{--                                    @endif--}}
{{--                                </td>--}}
{{--                                <td class="thick-table-border">--}}
                                <td>
                                    @if($input_field == 'weeks')
                                        {{ $user->workedInAWeekInHours(str_replace('W', '',explode('-', $weeks)[1])) }} uur
                                    @else
                                        {{ $user->WorkedInAMonthInHours(explode('-', $month)[1]) }} uur
                                    @endif
                                </td>
                                <td>
                                    @if($input_field == 'weeks')
                                        {{ $user->plannedWorkAWeekInHours(2021, str_replace('W', '',explode('-', $weeks)[1])) }} uur
                                    @else
                                        {{ $user->plannedWorkAMonthInHours(2021, explode('-', $month)[1]) }} uur
                                    @endif
                                </td>
                                <td @if($input_field == 'weeks')
                                        @if($user->compareWeekWorkedInSeconds(2021, str_replace('W', '',explode('-', $weeks)[1])) < 0) class="table-danger" @else class="table-success" @endif
                                    @else
                                        @if($user->compareMonthWorkedInSeconds(2021, explode('-', $month)[1]) < 0) class="table-danger" @else class="table-success" @endif
                                    @endif>
                                    @if($input_field == 'weeks')
                                        {{ $user->plannedWorkAWeekInHours(2021, str_replace('W', '',explode('-', $weeks)[1])) }} uur
                                    @else
                                        {{ $user->plannedWorkAMonthInHours(2021, explode('-', $month)[1]) }} uur
                                    @endif
                                </td>
{{--                                @if($input_field == 'weeks')--}}
{{--                                    <td @if($user->compareWeekWorkedInSeconds(2021, str_replace('W', '',explode('-', $weeks)[1])) < 0) class="table-danger" @else class="table-success" @endif>--}}
{{--                                        @if($user->compareWeekWorkedInSeconds(2021, str_replace('W', '',explode('-', $weeks)[1])) == 0)--}}
{{--                                            0 seconde--}}
{{--                                        @else--}}
{{--                                            {{ $user->compareWeekWorkedForHumans(2021, str_replace('W', '',explode('-', $weeks)[1])) }}--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
{{--                                @else--}}
{{--                                    <td @if($user->compareMonthWorkedInSeconds(2021, explode('-', $month)[1]) < 0) class="table-danger" @else class="table-success" @endif>--}}
{{--                                        @if($user->compareMonthWorkedInSeconds(2021, explode('-', $month)[1]) == 0)--}}
{{--                                            0 seconde--}}
{{--                                        @else--}}
{{--                                            {{ $user->compareMonthWorkedForHumans(2021, explode('-', $month)[1]) }}--}}
{{--                                        @endif--}}

{{--                                    </td>--}}
{{--                                @endif--}}
                                <td>
                                    @if($input_field == 'weeks')
                                        <a href="{{ route('admin.compare.show', ['user' => $user['id'], 'type' => $input_field, 'time' => $weeks]) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('admin.compare.show', ['user' => $user['id'], 'type' => $input_field, 'time' => $month]) }}">
                                            <i class="fa fa-eye"></i>
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
