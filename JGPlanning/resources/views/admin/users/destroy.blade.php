@extends('layouts.app')

@section('content')
    <h1>Delete {{$user['name']}}?</h1>
    <form method="get" action=""
    <button type="submit" class="btn btn-dark">Yes</button>
    <button type="submit" class="btn btn-dark">No</button>
@endsection
