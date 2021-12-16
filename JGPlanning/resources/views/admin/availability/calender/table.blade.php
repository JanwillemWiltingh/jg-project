@extends('layouts.app')

@section('content')
    <div class="container fadeInDown">
        <div class="col-md-8">
            <div class="card" style="padding: 15px 15px">
                <div>
                    <label style="display: inline-block">
                        <p>Kies een gebruiker waarvan je de rooster wilt bewerken</p>
                    </label>
{{--                    @if($plan_in)--}}
{{--                        <form method="POST" action="{{route('admin.rooster.plan_next_year')}}">--}}
{{--                            @csrf--}}
{{--                            <button type="submit" class="btn jg-color-1 " style="color: white !important; text-transform: capitalize !important; font-size: 12px !important; float: right !important; margin: 0 !important; padding: 5px 15px">Plan volgend jaar in voor elke gebruiker</button>--}}
{{--                        </form>--}}
{{--                    @endif--}}
                </div>
                <table class="data-table-custom" style="" id="admin_availability">
                    <thead>
                        <tr>
                            <th width="50%">Naam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($users->where('role_id', 3)->count() != 0)
                            @foreach($users->where('role_id', 3) as $user)
                                <tr>
                                    <td><a href="{{route('admin.rooster.user_rooster', ['user' => $user->id, 'week' => \Carbon\Carbon::now()->week + 1, 'year' => date('Y')])}}">{{$user['firstname']}} {{$user['middlename']}} {{$user['lastname']}} <i class="fa fa-arrow-right" style="text-align: right"></i></a></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>Er zijn nog geen werknemers om de roosters van te bekijken.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
