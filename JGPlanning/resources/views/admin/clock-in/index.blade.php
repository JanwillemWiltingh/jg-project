@extends('layouts.app')

@section('content')
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Gebruiker</th>
                    <th scope="col">Start tijd</th>
                    <th scope="col">Totaal gewerkt</th>
                </tr>
            </thead>
            <tbody>
                @if($user_id !== null)
                    @foreach($user_id as $id)
                        <tr>
                            <th scope="row">{{ $loop->index }}</th>
{{--                            <td>{{ $clock->user()->get()->first()->name }}</td>--}}
{{--                            <td>Otto</td>--}}
{{--                            <td>@mdo</td>--}}
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">No one has clocked in yet</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
