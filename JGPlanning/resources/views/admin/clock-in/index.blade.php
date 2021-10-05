@extends('layouts.app')

@section('content')
    <div class="container">
        <table class="table">
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
                        <td colspan="4">No one has clocked in yet</td>
                    </tr>
                @endif
            </tbody>
        </table>
        {{ $clocks->links() }}
    </div>
@endsection
