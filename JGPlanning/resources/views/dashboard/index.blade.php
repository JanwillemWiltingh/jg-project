@extends('layouts.app')

@section('content')
{{--  TODO: Mogelijk met Jessenger package controleren of iemand op mobiel bezig is of niet  --}}
{{--    Cards should imitate this: https://codepen.io/lesliesamafful/pen/oNXgmBG?editors=1010   --}}
    <div class="row">
        <div class="col-12">
            <h1>Welkom {{$user['firstname']}}!</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-stretch">
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-3x"></i>
                        </div>
                        <div class="media-body pl-3">
                            <h4>Uren gewerkt</h4>
                            <span>{{ $now->format('F') }} {{ $now->format('Y') }}</span>
                        </div>
                        <div class="align-self-center">
                            <h1>{{ number_format($user->workedInAMonth($now->month)[1] / 3600, 1) }}</h1>
                        </div>
                    </div>

                    <hr>

                    <div class="media align-items-stretch">
                        <div class="align-self-center">
                            <i class="fas fa-calendar fa-3x"></i>
                        </div>
                        <div class="media-body pl-3">
                            <h4>Rooster uren</h4>
                            <span>{{ $now->format('F') }} {{ $now->format('Y') }}</span>
                        </div>
                        <div class="align-self-center">
                            <h1>{{ number_format($user->plannedWorkAMonth($now->year, $now->month)[1] /3600, 1) }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-stretch">
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-3x"></i>
                        </div>
                        <div class="media-body pl-3">
                            <h4>Uren gewerkt</h4>
                            <span>Week {{ $now->weekOfYear }}, {{ $now->format('Y') }}</span>
                        </div>
                        <div class="align-self-center">
                            <h1>{{ number_format($user->workedInAWeek($now->weekOfYear)[1] / 3600, 1) }}</h1>
                        </div>
                    </div>

                    <hr>

                    <div class="media align-items-stretch">
                        <div class="align-self-center">
                            <i class="fas fa-calendar fa-3x"></i>
                        </div>
                        <div class="media-body pl-3">
                            <h4>Rooster uren</h4>
                            <span>Week {{ $now->weekOfYear }}, {{ $now->format('Y') }}</span>
                        </div>
                        <div class="align-self-center">
                            <h1>{{ number_format($user->plannedWorkAWeek($now->year, $now->weekOfYear)[1] /3600, 1) }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-stretch">
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-3x"></i>
                        </div>
                        <div class="media-body pl-3">
                            <h4>Uren gewerkt</h4>
                            <span>{{ $now->format('d F Y') }}</span>
                        </div>
                        <div class="align-self-center">
                            <h1>{{ number_format($user->workedInADay($now->year, $now->month, $now->day)[1] / 3600, 1) }}</h1>
                        </div>
                    </div>

                    <hr>

                    <div class="media align-items-stretch">
                        <div class="align-self-center">
                            <i class="fas fa-calendar fa-3x"></i>
                        </div>
                        <div class="media-body pl-3">
                            <h4>Rooster uren</h4>
                            <span>{{ $now->format('d F Y') }}</span>
                        </div>
                        <div class="align-self-center">
                            <h1>{{ number_format($user->plannedWorkADay($now->year, $now->weekOfYear, $now->dayOfWeek)[1] / 3600, 1) }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('dashboard.clock') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Aantekening</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="4" @if($start == False) @else DISABLED @endif></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @if($start)
                                    <button type="submit" class="btn btn-dark float-right">Clock Out</button>
                                @else
                                    <button type="submit" class="btn btn-dark float-right">Clock In</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media align-items-stretch">
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-3x"></i>
                                </div>
                                <div class="media-body pl-3">
                                    <h4>Uren voor vandaag</h4>
                                    <span>{{ $now->format('d F Y') }}</span>
                                </div>
                                <div class="align-self-center">
                                    <h1>{{ Carbon\Carbon::parse($user->getRoosterFromToday()['start_time'])->format('H:i') }} - {{ Carbon\Carbon::parse($user->getRoosterFromToday()['end_time'])->format('H:i') }}</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media align-items-stretch">
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-3x"></i>
                                </div>
                                <div class="media-body pl-3">
                                    <h4>Uren voor {{ App\Models\Availability::WEEK_DAYS[$user->getNextRooster()['weekdays']] }}</h4>
                                    <span>{{ $now->format('d F Y') }}</span>
                                </div>
                                <div class="align-self-center">
                                    <h1>{{ Carbon\Carbon::parse($user->getNextRooster()['start_time'])->format('H:i') }} - {{ Carbon\Carbon::parse($user->getNextRooster()['end_time'])->format('H:i') }}</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session()->get('error')) {{ session()->get('error') }} @endif
@endsection
