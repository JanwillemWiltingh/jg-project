@extends('layouts.app')

@section('content')
    <h1>Bewerk Aantal gewerkte uren Gebruiker <a href="{{route('admin.clock.index')}}" style="font-size: 30px;"><i class="fa-solid fa-backward-step icon-color"></i></a></h1>
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
                    <label class="black-label-text" for="start_time">Start Tijd</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" min="08:30" max="18:00" value="{{$clock['start_time']}}" aria-describedby="start_time" placeholder="Start Tijd">
                    @if($errors->has('start_time'))
                        <div class="error">{{ $errors->first('start_time') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="end_time">Eind Tijd</label>
                    <input type="time" class="form-control" id="end_time" name="end_time" min="08:30" max="18:00" value="{{$clock['end_time']}}" aria-describedby="end_time" placeholder="Eind Tijd">
                    @if($errors->has('end_time'))
                        <div class="error">{{ $errors->first('end_time') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="total_hours">Totaal aantal werkuren</label>
                    <input type="text" class="form-control" id="total_hours" value="{{round(($total_difference - 0.5), 1)}}" aria-describedby="total_hours" placeholder="Totaal aantal uren" readonly>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary jg-color-3 border-0" value="Opslaan">Opslaan</button>
    </form>
@endsection
