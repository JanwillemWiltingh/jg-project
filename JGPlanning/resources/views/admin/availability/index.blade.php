@extends('layouts.app')

@section('content')
    <div class="container fadeInDown">
        <div class="col-md-8">
            <div class="card" style="padding: 15px 15px">
                <label>
                    <p>Kies een gebruiker waarvan je de rooster wilt bewerken</p>
                </label>
                <table class="table table-bordered" id="admin_availability">
                    <thead>
                    <tr>
                        <th width="50%">Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td><a href="{{route('admin.available.user_availability', $user->id)}}">{{$user->name}} <i class="fa fa-arrow-right" style="text-align: right"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
