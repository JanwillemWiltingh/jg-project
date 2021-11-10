@extends('layouts.app')

@section('content')
        <div class="row">
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-12" style="margin-top: -32px !important;">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Selectie Opties</h4>
                                <form method="GET" action="{{ route('admin.clock.index') }}">
                                    <div class="form-group">
                                        <label for="users">Gebruikers</label>
                                        <select name="user" class="form-control" id="users">
                                            <option value="0">Alle Gebruikers</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user['id'] }}" @if(old('user') == $user['id'] or session('user') == $user['id']) selected @endif>{{ ucfirst($user['firstname']) }} {{ucfirst($user['lastname'])}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="form-group">
                                        <label for="date">Datum</label>
                                        <input name="date" id="date" type="date" class="form-control" value="{{ old('date') ?? session('date') ?? $now }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Selecteer</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <table id="table" class="table table-striped table-hover" style="box-shadow: 0 0 5px 0 lightgrey;">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Gebruiker</th>
                        <th scope="col">Start tijd</th>
                        <th scope="col">Eind tijd</th>
                        <th scope="col">Totaal ingeklokt</th>
                        <th scope="col">Aantekening</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($clocks->count() != 0)
                        @foreach($clocks as $clock)
                            <tr>
                                <th scope="row">{{ $loop->index }}</th>
                                <td>{{ $clock->user()->get()->first()['firstname'] }} {{ $clock->user()->get()->first()['middlename'] }} {{ $clock->user()->get()->first()['lastname'] }}</td>
                                <td>{{ $clock->reformatTime('start_time') }}</td>
                                <td>{{ $clock->reformatTime('end_time') }}</td>
                                <td>{{ $clock->timeWorkedToday(false)}}</td>
                                <td>{{ $clock['comment'] }}</td>
                                @if($user_session['role_id'] == App\Models\Role::getRoleID('maintainer') && !empty($clock['end_time']))
                                    <td><a class="table-label" href="{{route('admin.clock.edit', $clock['id'])}}"><i class="fa-solid fa-user-pen"></i></a></td>
                                @else
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">Werkenemers zijn/hebben nog ingeklokked</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{$clocks->links()}}
                </div>
            </div>
        </div>
@endsection
