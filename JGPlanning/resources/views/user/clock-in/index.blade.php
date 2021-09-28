@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('clocker.clock') }}" method="post">
            @csrf

            @if($start == False)
                <button type="submit" class="btn btn-dark">Clock In</button>
            @else
                <button type="submit" class="btn btn-dark">Clock Out</button>
            @endif
        </form>
    </div>
@endsection
