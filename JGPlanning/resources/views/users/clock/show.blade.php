@extends('layouts.app')

@section('content')
    <div class="crud-user-form fadeInDown">
        <h1>Klok Informatie <a href="{{route('users.clock.index')}}" style="font-size: 30px;"><i class="fa-solid fa-backward-step icon-color"></i></a></h1>
        <div class="card">
            <div class="card-body">
                {{--  CLOCK ID  --}}
                <div class="row">
                    <div class="form-group">
                        <label class="black-label-text" for="id">Klok Id</label>
                        <input type="text" class="form-control" id="id" value="@if(empty($clock['id']))NULL @else{{$clock['id']}} @endif" aria-describedby="id" placeholder="Id" disabled>
                    </div>
                </div>
                {{--  USER ID  --}}
                <div class="row">
                    <div class="form-group">
                        <label class="black-label-text" for="firstname">User Id</label>
                        <input type="text" class="form-control" id="firstname" value="@if(empty($clock['user_id']))NULL @else{{$clock['user_id']}} @endif" aria-describedby="firstname" placeholder="Voornaam" disabled>
                    </div>
                </div>
                {{--  CLOCK COMMENT  --}}
                <div class="row">
                    <div class="form-group">
                        <label class="black-label-text" for="middlename">Aantekening</label>
                        <input type="text" class="form-control" id="middlename" value="@if(empty($clock['comment']))NULL @else{{$clock['comment']}} @endif" aria-describedby="middlename" placeholder="Tussenvoegsel" disabled>
                    </div>
                </div>
                {{--  CLOCK START TIME  --}}
                <div class="row">
                    <div class="form-group">
                        <label class="black-label-text" for="lastname">Start Tijd</label>
                        <input type="time" class="form-control" id="lastname" value="@if(empty($clock['start_time']))NULL @else{{$clock['start_time']}} @endif" aria-describedby="lastname" placeholder="Achternaam" disabled>
                    </div>
                </div>
                {{--  CLOCK END TIME  --}}
                <div class="row">
                    <div class="form-group">
                        <label class="black-label-text" for="email">Eind Tijd</label>
                        <input type="time" class="form-control" id="email" value="@if(empty($clock['end_time']))NULL @else{{$clock['end_time']}} @endif" aria-describedby="email" placeholder="E-mail" disabled>
                    </div>
                </div>
                {{--  CLOCK DATE  --}}
                <div class="row">
                    <div class="form-group">
                        <label class="black-label-text" for="created_at">Datum</label>
                        <input type="date" class="form-control" id="created_at" value="@if(empty($clock['date'])) - @else{{$clock['date']}} @endif" aria-describedby="created_at" placeholder="Gebruiker Gecreëerd" disabled>
                    </div>
                </div>
                {{--  CLOCK DELETED AT  --}}
                <div class="row">
                    <div class="form-group">
                        <label class="black-label-text" for="updated_at">Verwijderd op</label>
                        <input type="text" class="form-control" id="updated_at" value="@if(empty($clock['deleted_at'])) - @else{{$clock['deleted_at']}} @endif" aria-describedby="updated_at" placeholder="Laatst Bijgewerkt" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
