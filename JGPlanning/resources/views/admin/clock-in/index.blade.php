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

                                    <!-- Single User selector -->
                                    <div class="form-group">
                                        <label for="users">Gebruikers</label>
                                        <select name="user" class="form-control" id="users">
                                            <option value="0">Alle Gebruikers</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user['id'] }}" @if(old('user') == $user['id'] or session('user') == $user['id']) selected @endif>{{ ucfirst($user['firstname']) }} {{ucfirst($user['lastname'])}}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <!-- Date Picker -->
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

            <!-- Table -->
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
                            <tr @if($loop->index % 2 == 0) class="table-light" @endif>
                                <!-- Table index -->
                                <th scope="row">{{ $loop->index + 1 }}</th>

                                <!-- User full name -->
                                <td>{{ $clock->getUserData('firstname') }} {{ $clock->getUserData('middlename') }} {{ $clock->getUserData('lastname') }}</td>

                                <!-- Start and End time of clock -->
                                <td>{{ $clock->reformatTime('start_time') }}</td>
                                <td>{{ $clock->reformatTime('end_time') }}</td>

                                <!-- Time between Start and End time -->
                                <td>{{ $clock->timeWorkedInHours($clock['date']) }} uur</td>

                                <!-- Comment given with Start time -->
                                <td>{!! $clock['comment'] !!}</td>

                                <!-- Edit button if user is maintainer -->
                                @if($clock->allowedToEdit('maintainer'))
                                    <td><a class="table-label" href="{{route('admin.clock.edit', $clock['id'])}}"><i class="fa-solid fa-user-pen"></i></a></td>
                                @else
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <!-- Table Row for when there is noone who has clocked in -->
                        <tr>
                            <td colspan="6">Werkenemers zijn/hebben nog niet ingeklokd</td>
                        </tr>
                    @endif
                    </tbody>
                </table>

                <!-- Pagination tabs -->
                <div class="d-flex justify-content-center">
                    {{$clocks->links()}}
                </div>
            </div>
        </div>
@endsection
