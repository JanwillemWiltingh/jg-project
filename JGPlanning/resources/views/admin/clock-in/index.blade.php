@extends('layouts.app')

@section('content')

{{--    <div class="container">--}}
        <div class="row">
            <div class="col-md-2">
                <div class="row">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('admin.clock.index') }}">
                            <div class="form-group">
                                <label for="users">Gebruikers</label>
                                <select name="user" class="form-control" id="users">
                                    <option value="0">Alle Gebruikers</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user['id'] }}" @if(old('user') == $user['id'] or session('user') == $user['id']) selected @endif>{{ ucfirst($user['name']) }}</option>
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
            <div class="col-md-10">
                <table id="table" class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Gebruiker</th>
                        <th scope="col">Start tijd</th>
                        <th scope="col">Eind tijd</th>
                        <th scope="col">Totaal ingeklokt</th>
                        <th scope="col">Aantekening</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($clocks->count() != 0)
                        @foreach($clocks as $clock)
                            <tr>
                                <th scope="row">{{ $loop->index }}</th>
                                <td>{{ ucfirst($clock->user()->get()->first()['firstname']) }} {{ $clock->user()->get()->first()['middlename'] }} {{ ucfirst($clock->user()->get()->first()['lastname']) }}</td>
                                <td>{{ $clock->reformatTime('start_time') }}</td>
                                <td>{{ $clock->reformatTime('end_time') }}</td>
                                <td>{{ $clock->timeWorkedToday(false) }}</td>
                                <td>{{ $clock['comment'] }}</td>
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
{{--    </div>--}}
@endsection
