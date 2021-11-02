@extends('layouts.app')

@section('content')
{{--    Cards should imitate this: https://codepen.io/lesliesamafful/pen/oNXgmBG?editors=1010   --}}

    <div class="row">
        <div class="col-12">
            <div class="dashboard-welkom">
                <h1>Welkom </h1>
                <a>{{$user['firstname']}}!</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-body gradient-dashboard">
                    <div class="media align-items-stretch" >
                        <div class="align-self-center">
                            <i class="far fa-clock fa-4x"></i>
                        </div>
                        <div class="media-body pl-3">
                            <h4 class="dashboard-title-hours">Uren gewerkt</h4>
                            <span class="dashboard-title-hours">{{ $now->format('F') }} {{ $now->format('Y') }}</span>
                        </div>
                        <div class="align-self-center">
                            <h1 class="dashboard-hours">{{ number_format($user->workedInAMonth($now->month)[1] / 3600, 1) }}</h1>
                        </div>
                    </div>

                    <hr>

                    <div class="media align-items-stretch">
                        <div class="align-self-center">
                            <i class="far fa-calendar fa-4x"></i>
                        </div>
                        <div class="media-body pl-3">
                            <h4 class="dashboard-title-hours">Rooster uren</h4>
                            <span class="dashboard-title-hours">{{ $now->format('F') }} {{ $now->format('Y') }}</span>
                        </div>
                        <div class="align-self-center">
                            <h1 class="dashboard-title-hours">{{ number_format($user->plannedWorkAMonth($now->year, $now->month)[1] /3600, 1) }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-body gradient-dashboard">
                    <div class="media align-items-stretch">
                        <div class="align-self-center">
                            <i class="far fa-clock fa-4x font-weight-lighter"></i>
                        </div>
                        <div class="media-body pl-3">
                            <h4 class="dashboard-title-hours">Uren gewerkt</h4>
                            <span class="dashboard-title-hours">Week {{ $now->weekOfYear }}, {{ $now->format('Y') }}</span>
                        </div>
                        <div class="align-self-center">
                            <h1 class="dashboard-title-hours">{{ number_format($user->workedInAWeek($now->weekOfYear)[1] / 3600, 1) }}</h1>
                        </div>
                    </div>

                    <hr>

                    <div class="media align-items-stretch">
                        <div class="align-self-center">
                            <i class="far fa-calendar fa-4x"></i>
                        </div>
                        <div class="media-body pl-3">
                            <h4 class="dashboard-title-hours">Rooster uren</h4>
                            <span class="dashboard-title-hours">Week {{ $now->weekOfYear }}, {{ $now->format('Y') }}</span>
                        </div>
                        <div class="align-self-center">
                            <h1 class="dashboard-title-hours">{{ number_format($user->plannedWorkAWeek($now->year, $now->weekOfYear)[1] /3600, 1) }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-body gradient-dashboard">
                    <div class="media align-items-stretch">
                        <div class="align-self-center">
                            <i class="far fa-clock fa-4x font-weight-lighter"></i>
                        </div>
                        <div class="media-body pl-3">
                            <h4 class="dashboard-title-hours">Uren gewerkt</h4>
                            <span class="dashboard-title-hours">{{ $now->format('d F Y') }}</span>
                        </div>
                        <div class="align-self-center">
                            <h1 class="dashboard-title-hours">{{ number_format($user->workedInADay($now->year, $now->month, $now->day)[1] / 3600, 1) }}</h1>
                        </div>
                    </div>

                    <hr>

                    <div class="media align-items-stretch">
                        <div class="align-self-center">
                            <i class="far fa-calendar fa-4x"></i>
                        </div>
                        <div class="media-body pl-3">
                            <h4 class="dashboard-title-hours">Rooster uren</h4>
                            <span class="dashboard-title-hours">{{ $now->format('d F Y') }}</span>
                        </div>
                        <div class="align-self-center">
                            <h1 class="dashboard-title-hours">{{ number_format($user->plannedWorkADay($now->year, $now->weekOfYear, $now->dayOfWeek)[1] / 3600, 1) }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body ">
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
                                    <button type="submit" class="btn btn-dark float-right" style="background: #CB6827 !important; border-color:  #CB6827 !important;">Clock Out</button>
                                @else
                                    <button type="submit" class="btn btn-dark float-right jg-color-2 border-0" @if(!$allowed) DISABLED @endif>Clock In</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="row">
                <div class="col-12" style="margin-bottom: -31px !important">
                    <div class="card" style="height: 72% !important;">
                        <div class="card-body gradient-dashboard">
                            <div class="media align-items-stretch">
                                <div class="align-self-center">
                                    <i class="far fa-clock fa-4x font-weight-lighter"></i>
                                </div>
                                <div class="media-body pl-3">
                                    <h4>Uren voor vandaag</h4>
                                    @if($user->getRoosterFromToday()['start_time'] != '00:00')
                                        <span>{{ $now->format('d F Y') }}</span>
                                    @else
                                        <span>Geen rooster voor vandaag</span>
                                    @endif
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
                    <div class="card" style="height: 72% !important;">
                        <div class="card-body gradient-dashboard">
                            <div class="media align-items-stretch">
                                <div class="align-self-center">
                                    <i class="far fa-clock fa-4x"></i>
                                </div>
                                <div class="media-body pl-3">
                                    <h4>Rooster voor {{ App\Models\Availability::WEEK_DAYS[$user->getNextRooster()['weekdays']] }}</h4>
                                    @if($user->getNextRooster()['weekdays'] == 0)
                                        <span>Geen nieuwe rooster</span>
                                    @else
                                        <span>{{ $now->format('d F Y') }}</span>
                                    @endif
                                </div>
                                <div class="align-self-center">
                                    @if($user->getNextRooster()['weekdays'] == 0)
                                        <h1>{{ $user->getNextRooster()['start_time'] }} - {{ $user->getNextRooster()['end_time'] }}</h1>
                                    @else
                                        <h1>{{ Carbon\Carbon::parse($user->getNextRooster()['start_time'])->format('H:i') }} - {{ Carbon\Carbon::parse($user->getNextRooster()['end_time'])->format('H:i') }}</h1>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<a style="color: white; cursor: pointer" href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">.</a>
@endsection
