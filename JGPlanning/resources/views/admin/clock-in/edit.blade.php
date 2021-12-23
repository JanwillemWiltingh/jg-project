@extends('layouts.app')

@section('content')
<div class="crud-user-form fadeInDown">
    <h1>Bewerk gewerkte uren gebruiker</h1>
    <div class="card">
        <div class="card-body">
            <form method="get" action="{{ route('admin.clock.update', $clock['id']) }}">
                <div class="row">
                    <div class="form-group">
                        <label class="black-label-text" for="firstname">Voornaam</label>
                        <input type="text" class="form-control" id="firstname" value="{{$clock->user()->get()->first()['firstname']}}" aria-describedby="firstname" placeholder="Voornaam" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label class="black-label-text" for="email">E-mail</label>
                        <input type="email" class="form-control" id="email" value="{{$clock->user()->get()->first()['email']}}" aria-describedby="email" placeholder="E-mail" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label class="black-label-text" for="time_start">Start tijd</label>
                        <div>
                            <input type="number" class="form-control" id="time_start" name="start_tijd_uren" min="1" max="18" value="{{substr($clock['start_time'], 0 , -6)}}" aria-describedby="time_start" placeholder="Start Tijd" style="width: 49%; display: inline-block">
                            <input type="number" class="form-control" id="time_start" name="start_tijd_minuten" min="0" max="60" value="{{substr(substr($clock['start_time'], 0 , -3), 3 , 3)}}" aria-describedby="time_start" placeholder="Start Tijd" style="width: 49%; display: inline-block">
                        </div>
                        @if($errors->has('time_start'))
                            <div class="error">{{ $errors->first('time_start') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label class="black-label-text" for="time_end">Eind tijd</label>
                        <div>
                            <input type="number" class="form-control" id="time_end" name="eind_tijd_uren" min="1" max="18" value="{{substr($clock['end_time'], 0 , -6)}}" aria-describedby="time_end" placeholder="Eind Tijd" style="width: 49%; display: inline-block">
                            <input type="number" class="form-control" id="time_end" name="eind_tijd_minuten" min="0" max="60" value="{{substr(substr($clock['end_time'], 0 , -3), 3 , 3)}}" aria-describedby="time_end" placeholder="Eind Tijd" style="width: 49%; display: inline-block">
                        </div>
                        @if($errors->has('time_end'))
                            <div class="error">{{ $errors->first('time_end') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label class="black-label-text" for="total_hours">Totaal aantal werkuren</label>
                        <input type="text" class="form-control" id="total_hours" value="{{round(($total_difference - 0.5), 1)}}" aria-describedby="total_hours" placeholder="Totaal aantal uren" readonly>
                    </div>
                </div>
                <button class="btn btn-primary jg-color-3 border-0" value="Ga terug"><a href="{{route('admin.clock.index')}}" style="text-decoration: none; color: white;">Ga terug</a></button>
                <button style="float: right" type="submit" class="btn btn-primary jg-color-3 border-0" value="Opslaan">Opslaan</button>
            </form>
@endsection
