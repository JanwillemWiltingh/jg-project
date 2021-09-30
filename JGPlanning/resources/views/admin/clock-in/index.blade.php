@extends('layouts.app')

@section('content')
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Time</th>
                    <th scope="col">Start</th>
                    <th scope="col">Comment</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clocks as $clock)
                    <tr>
                        <th scope="row">{{ $loop->index }}</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
