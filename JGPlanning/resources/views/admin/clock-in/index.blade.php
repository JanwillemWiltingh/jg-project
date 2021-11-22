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
                                    <button type="submit" class="btn btn-primary jg-color-3 border-0">Selecteer</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="col-md-9">
                <table id="table" class="table table-hover" style="box-shadow: 0 0 5px 0 lightgrey;">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Gebruiker</th>
                        <th scope="col">Start tijd</th>
                        <th scope="col">Eind tijd</th>
                        <th scope="col">Totaal ingeklokt</th>
                        <th scope="col">Aantekening</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($clocks->count() != 0)
                        @foreach($clocks as $clock)
                            <tr @if($clock['deleted_at']) class="table-danger" @endif>
                                <!-- Table index -->
                                <th scope="row">{{ $loop->index + 1 }}</th>

                                <!-- User full name -->
                                <td>{{ $clock->getUserData('firstname') }} {{ $clock->getUserData('middlename') }} {{ $clock->getUserData('lastname') }}</td>

                                <!-- Start and End time of clock -->
                                <td>{{ $clock->reformatTime('start_time') }}</td>
                                <td>{{ $clock->reformatTime('end_time') }}</td>

                                <!-- Time between Start and End time -->
                                <td>{{ $clock->timeWorkedInHours($clock['start_time'], $clock['end_time'], 2) }} uur</td>

                                <!-- Comment given with Start time -->
                                <td>{!! $clock['comment'] !!}</td>
                                <!-- Edit button if user is maintainer -->
                                @if($clock->allowedToEdit('maintainer'))
                                    <td><a class="table-label" href="{{route('admin.clock.edit', $clock['id'])}}"><i class="fa-solid fa-user-pen icon-color"></i></a></td>
                                @else
                                    <td></td>
                                @endif

                                @if(empty($clock['deleted_at']))
                                {{--If NOT deleted--}}
                                    <td><a class="table-label-red" href="{{route('admin.clock.destroy',$clock['id'])}}" data-toggle="tooltip" title="Gebruikers Uren Verwijderen"><i class="fa-solid fa-user-slash"></i></a></td>
                                @else
                                {{--If deleted--}}
                                    <td><a class="table-label-green" href="{{route('admin.clock.destroy',$clock['id'])}}" data-toggle="tooltip" title="Gebruiker Herstellen"><i class="fa-solid fa-user-check"></i></a></td>
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
