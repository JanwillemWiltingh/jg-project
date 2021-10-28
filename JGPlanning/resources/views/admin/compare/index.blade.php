@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-2">
            <div class="row">
                <div class="col-md-12">
                    <form method="GET" action="{{ route('admin.compare.index') }}">

                        <div class="form-group">
                            <label for="users">Gebruikers</label>
                            <select name="user" class="form-control" id="users">
                                <option value="0">Alle Gebruikers</option>
                                @foreach($all_users as $user)
                                    <option value="{{ $user['id'] }}" @if(old('user') == $user['id'] or session('user') == $user['id']) selected @endif>{{ ucfirst($user['firstname']) }} {{ ucfirst($user['lastname']) }}</option>
                                @endforeach
                            </select>
                        </div>

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

                        <div class="form-group" id="month-group" @if($input_field != 'month') style="display: none;" @endif>
                            <label for="month">Maand</label>
                            <input name="month" id="month" type="month" class="form-control" value="{{ old('month') ?? session('month') ?? $month }}">
                        </div>

                        <div class="form-group" id="week-group" @if($input_field != 'weeks') style="display: none;" @endif>
                            <label for="weeks">Week</label>
                            <input name="weeks" id="weeks" type="weeks" class="form-control" value="{{ old('weeks') ?? session('weeks') ?? $weeks }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Selecteer</button>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Naam</th>
                            <th scope="col">Tijd Gewerkt</th>
                            <th scope="col"></th>
                            <th scope="col">Tijd Ingepland</th>
                            <th scope="col"></th>
                            <th scope="col">Verschil</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <th scope="row">{{ $loop->index }}</th>
                                <td class="thick-table-border">
                                    {{ $user['firstname'] }} {{ $user['middlename'] }} {{ $user['lastname'] }}
                                </td>
                                <td>
                                    @if($input_field == 'week')
                                        {{ $user->workedInAWeek(str_replace('W', '',explode('-', $week)[1]))[0] }}
                                    @else
                                        {{ $user->workedInAMonth(explode('-', $month)[1])[0] }}
                                    @endif
                                </td>
                                <td class="thick-table-border">
                                    @if($input_field == 'week')
                                        {{ number_format($user->workedInAWeek(str_replace('W', '',explode('-', $week)[1]))[1] / 3600, 1) }} uur
                                    @else
                                        {{ number_format($user->workedInAMonth(explode('-', $month)[1])[1] / 3600, 1) }} uur
                                    @endif
                                </td>
                                <td>
                                    @if($input_field == 'week')
                                        {{ $user->plannedWorkAWeek(2021, str_replace('W', '',explode('-', $week)[1]))[0] }}
                                    @else
                                        {{ $user->plannedWorkAMonth(2021, explode('-', $month)[1])[0] }}
                                    @endif
                                </td>
                                <td>
                                    @if($input_field == 'week')
                                        {{ number_format($user->plannedWorkAWeek(2021, str_replace('W', '',explode('-', $week)[1]))[1] / 3600, 1) }} uur
                                    @else
                                        {{ number_format($user->plannedWorkAMonth(2021, explode('-', $month)[1])[1] / 3600, 1) }} uur
                                    @endif
                                </td>
                                @if($input_field == 'week')
                                    <td @if($user->compareWeekWorked(2021, str_replace('W', '',explode('-', $week)[1]))[1] < 0) class="table-danger" @else class="table-success" @endif>
                                        @if($user->compareWeekWorked(2021, str_replace('W', '',explode('-', $week)[1]))[1] == 0)
                                            0 seconde
                                        @else
                                            {{ $user->compareWeekWorked(2021, str_replace('W', '',explode('-', $week)[1]))[0] }}
                                        @endif
                                    </td>
                                @else
                                    <td @if($user->compareMonthWorked(2021, explode('-', $month)[1]) < 0) class="table-danger" @else class="table-success" @endif>
                                        @if($user->compareMonthWorked(2021, explode('-', $month)[1])[1] == 0)
                                            0 seconde
                                        @else
                                            {{ $user->compareMonthWorked(2021, explode('-', $month)[1])[0] }}
                                        @endif

                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
