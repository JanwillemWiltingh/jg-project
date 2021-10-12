@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('dashboard.clock') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="comment" class="form-label">Aantekening</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" @if($start == False) @else DISABLED @endif></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @if($start == False)
                        <button type="submit" class="btn btn-dark">Clock In</button>
                    @else
                        <button type="submit" class="btn btn-dark">Clock Out</button>
                    @endif
                </div>
            </div>
        </form>
    </div>

    @if(session()->get('error')) {{ session()->get('error') }} @endif
@endsection
