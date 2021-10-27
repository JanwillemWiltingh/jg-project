@extends('layouts.app')

@section('content')
    @if(session()->get('message'))
        <div class="alert alert-{{ session()->get('message')['type'] }} alert-dismissible fade show" role="alert">
            {{ session()->get('message')['message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif
    <h1>Bewerk Aantal gewerkte uren Gebruiker <a href="{{route('admin.clock.index')}}" style="font-size: 30px;"><i class="fa-solid fa-backward-step"></i></a></h1>
    <form method="get" action="{{ route('admin.clock.update', $user['id']) }}">
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="firstname">Voornaam</label>
                    <input type="text" class="form-control" id="firstname" value="{{$user['firstname']}}" aria-describedby="firstname" placeholder="Voornaam" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" value="{{$user['email']}}" aria-describedby="email" placeholder="E-mail" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="start_time">Start Tijd</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" value="{{$clock_by_id->start_time}}" aria-describedby="start_time" placeholder="Start Tijd">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="end_time">Eind Tijd</label>
                    <input type="time" class="form-control" id="end_time" name="end_time" value="{{$clock_by_id->end_time}}" aria-describedby="end_time" placeholder="Eind Tijd">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="black-label-text" for="total_hours">Totaal aantal uren</label>
                    <input type="number" class="form-control" name="total_hours" min="0" max="9" step="0.25" id="total_hours" value="{{}}" aria-describedby="total_hours" placeholder="Totaal aantal uren">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" value="Opslaan">Opslaan</button>
    </form>
@endsection
