@extends('layouts.app')

@section('content')
    <h1>Bewerk Aantal gewerkte uren Gebruiker <a href="{{route('admin.clock.index')}}" style="font-size: 30px;"><i class="fa-solid fa-backward-step"></i></a></h1>
    <form method="get" action="{{ route('admin.clock.update', $clock['id']) }}">
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="firstname">Voornaam</label>
                    <input type="text" class="form-control" id="firstname" value="{{$clock->user()->get()->first()['firstname']}}" aria-describedby="firstname" placeholder="Voornaam" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" value="{{$clock->user()->get()->first()['email']}}" aria-describedby="email" placeholder="E-mail" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="time_start">Start Tijd</label>
                    <input type="time" class="form-control" id="time_start" name="time_start" min="08:30" max="18:00" value="{{$clock['start_time']}}" aria-describedby="time_start" placeholder="Start Tijd">
                    @if($errors->has('time_start'))
                        <div class="error">{{ $errors->first('time_start') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="time_end">Eind Tijd</label>
                    <input type="time" class="form-control" id="time_end" name="time_end" min="08:30" max="18:00" value="{{$clock['end_time']}}" aria-describedby="time_end" placeholder="Eind Tijd">
                    @if($errors->has('time_end'))
                        <div class="error">{{ $errors->first('time_end') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="total_hours">Totaal aantal werkuren</label>
                    <input type="text" class="form-control" id="total_hours" value="{{round($total_difference, 1)}}" aria-describedby="total_hours" placeholder="Totaal aantal uren" readonly>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" value="Opslaan">Opslaan</button>
    </form>
@endsection
