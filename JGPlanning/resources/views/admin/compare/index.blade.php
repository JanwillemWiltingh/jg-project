@extends('layouts.app')

@section('content')
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
                        <div class="form-group">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-primary">Week</button>
                                <button type="button" class="btn btn-primary">Maand</button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Selecteer</button>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Gebruikersnaam</th>
                            <th scope="col">Tijd Gewerkt</th>
                            <th scope="col">Tijd Ingepland</th>
                            <th scope="col">Verschil</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <th scope="row">{{ $loop->index }}</th>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user->workedInAMonth(10)[0] }}</td>
                                <td>2</td>
                                <td>3</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
