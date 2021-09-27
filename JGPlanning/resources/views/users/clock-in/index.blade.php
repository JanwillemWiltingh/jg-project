@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('clocker.clock') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-dark">Clock In</button>
        </form>
    </div>
@endsection
