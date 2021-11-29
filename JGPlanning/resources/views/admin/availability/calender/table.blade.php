@extends('layouts.app')

@section('content')
    <div class="container fadeInDown">
        <div class="col-md-8">
            <div class="card" style="padding: 15px 15px">
                <label>
                    <p>Kies een gebruiker waarvan je de rooster wilt bewerken</p>
                </label>
                <table class="data-table-custom" style="" id="admin_availability">
                    <thead>
                        <tr>
                            <th width="50%">Name</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td><a href="{{route('admin.rooster.user_rooster', ['user' => $user->id, 'week' => \Carbon\Carbon::now()->week, 'year' => date('Y')])}}">{{$user['firstname']}} {{$user['lastname']}} <i class="fa fa-arrow-right" style="text-align: right"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
