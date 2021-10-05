@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Gebruikers</label>
                    <select class="form-control" id="exampleFormControlSelect1">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Gebruiker</th>
                        <th scope="col">Start tijd</th>
                        <th scope="col">Eind tijd</th>
                        <th scope="col">Totaal gewerkt</th>
                        <th scope="col">Aantekening</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($clocks->count() != 0)
                        @foreach($clocks as $clock)
                            <tr>
                                <th scope="row">{{ $loop->index }}</th>
                                <td>{{ $clock->user()->get()->first()['name'] }}</td>
                                <td>{{ $clock->reformatTime('start_time') }}</td>
                                <td>{{ $clock->reformatTime('end_time') }}</td>
                                <td>{{ $clock->timeWorkedToday() }}</td>
                                <td>{{ $clock['comment'] }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">Niemand is nog ingeklokked</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{$clocks->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection
