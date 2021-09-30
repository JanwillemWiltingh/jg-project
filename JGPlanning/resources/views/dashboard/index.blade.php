@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('dashboard.clock') }}" method="post">
            @csrf

            @if($start == False)
                <button type="submit" class="btn btn-dark">Clock In</button>
            @else
                <button type="submit" class="btn btn-dark">Clock Out</button>
            @endif
        </form>
    </div>

    @if(session()->get('error')) {{ session()->get('error') }} @endif
@endsection
