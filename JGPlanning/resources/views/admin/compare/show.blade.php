@extends('layouts.app')

@section('content')
<div class="fadeInDown">
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
            <table class="table table-hover table-striped" style="box-shadow: 0 0 5px 0 lightgrey;">
                <thead>
                    <tr>
                        <th scope="col">Datum</th>
                        <th scope="col">Dag</th>
                        <th scope="col">Rooster uren</th>
                        <th scope="col">Tijd gewerkt</th>
                    </tr>
                </thead>
                <tbody>
                    @if($days->count() > 0)
                        @foreach($days as $day)
                            @if($user->plannedWorkADayInSeconds($day->format('Y'), $day->weekOfYear, $day->format('d')) > 0 or $user->workedInADayInSeconds($day->format('Y'), $day->format('m'), $day->format('d')) > 0)
                                <tr>
                                    <td>{{ $day->format('Y-m-d') }}</td>
                                    <td>{{ App\Models\Availability::WEEK_DAYS[$day->dayOfWeek] }}</td>
                                    <td>{{ $user->plannedWorkADayInHours($day->format('Y'), $day->weekOfYear, $day->format('d')) }}</td>
                                    <td>{{ $user->workedInADayInHours($day->format('Y'), $day->format('m'), $day->format('d')) }} uur</td>
                                </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">{{ $user['firstname'] }} heeft nog niet gewerkt en is nog niet ingepland</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <!-- Pagination tabs -->
            <div class="d-flex justify-content-center">
                {{$days->links()}}
            </div>
        </div>
    </div>
    <button class="btn btn-primary jg-color-3 border-0" value="Ga terug"><a href="{{route('admin.compare.index')}}" style="text-decoration: none; color: white;">Ga terug</a></button>
</div>
@endsection
