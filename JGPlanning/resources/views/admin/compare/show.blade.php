@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1>
                {{ $user['firstname'] }} {{ $user['middlename'] }} {{ $user['lastname'] }}
                <br>
                <small class="text-muted">{{ $time }}</small>
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Datum</th>
                        <th scope="col">Dag</th>
                        <th scope="col">Tijd Ingeplanned</th>
                        <th scope="col">Tijd Ingeplanned met pauze</th>
                        <th scope="col">Tijd Gewerkt</th>
                        <th scope="col">Tijd Gewerkt met pauze</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($days as $day)
                        @if($user->plannedWorkADayInSeconds($day->format('Y'), $day->weekOfYear, $day->format('d')) > 0 or $user->workedInADayInSeconds($day->format('Y'), $day->format('m'), $day->format('d')) > 0)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ $day->format('Y-m-d') }}</td>
                                <td>{{ App\Models\Availability::WEEK_DAYS[$day->dayOfWeek] }}</td>
                                <td>{{ $user->plannedWorkADayInHours($day->format('Y'), $day->weekOfYear, $day->format('d')) }}</td>
                                <td>{{ $user->plannedWorkADayInHours($day->format('Y'), $day->weekOfYear, $day->format('d')) - .5 }}</td>
                                <td>{{ $user->workedInADayInHours($day->format('Y'), $day->format('m'), $day->format('d')) }}</td>
                                <td>{{ $user->workedInADayInHours($day->format('Y'), $day->format('m'), $day->format('d')) - .5 }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
