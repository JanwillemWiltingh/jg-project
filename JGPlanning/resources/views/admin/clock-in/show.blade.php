@extends('layouts.app')

@section('content')
    <div class="crud-user-form fadeInDown" style="left: 20%; width: 60%">
        <h1>Klok informatie</h1>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                            {{-- Start Tijd --}}
                                <div class="form-group">
                                    <label class="black-label-text" for="start_time">Start tijd</label>
                                    <input type="text" class="form-control" id="start_time" value="@if(empty($clock['start_time']))- @else{{substr($clock['start_time'], 0, -3)}}@endif" aria-describedby="start_time" placeholder="Start Tijd" disabled>
                                </div>
                            </div>

                            <div class="col-6">
                            {{-- Eind Tijd --}}
                                <div class="form-group">
                                    <label class="black-label-text" for="end_time">Eind tijd</label>
                                    <input type="text" class="form-control" id="end_time" value="@if(empty($clock['end_time']))- @else{{substr($clock['end_time'], 0, -3)}}@endif" aria-describedby="end_time" placeholder="Eind Tijd" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="row-6">
                            {{-- Datum --}}
                                <div class="form-group">
                                    <label class="black-label-text" for="date">Datum</label>
                                    <input type="date" class="form-control" id="date" min="01-01-2000" max="31-12-2100" value="@if(empty($clock->date)) - @else{{$clock->date}}@endif" aria-describedby="date" placeholder="Datum" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                    {{-- Aantekening --}}
                        <div class="form-group">
                            <label class="black-label-text" for="comment">Aantekening</label>
                            <textarea class="form-control" id="comment" aria-describedby="comment" rows="5" cols="80" disabled>@if(empty($clock['comment']))NULL @else{{$clock['comment']}}@endif</textarea>
                        </div>
                    </div>
                </div>
                {{--  CLOCK DELETED AT  --}}
                <div class="row">
                    <div class="form-group">
                        <label class="black-label-text" for="deleted_at">Verwijderd op</label>
                        <input type="text" class="form-control" id="deleted_at" value="@if(empty($clock['deleted_at'])) - @else{{$clock['deleted_at']}}@endif" aria-describedby="deleted_at" placeholder="Verwijderd op" disabled>
                    </div>
                </div>
                <button class="btn btn-primary jg-color-3 border-0" value="Ga Terug"><a href="{{route('admin.clock.index')}}" style="text-decoration: none; color: white;">Ga terug</a></button>
            </div>
        </div>
    </div>
@endsection
