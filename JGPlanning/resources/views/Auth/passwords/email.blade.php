@extends('layouts.guest')
@section('content')
    <img src="{{asset('storage/img/BG.png')}}" style="width: 100%; height: 100%; position: absolute; margin-left: -100px;">
    <div class="reset-page fadeInDown">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <x-alert></x-alert>

                <a href="/"><img src="{{asset('storage/img/JG Rooster v2.png')}}" alt="JG planning"></a>

                <div class="card">
                    <div class="card-header">Reset wachtwoord</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{route('forget.password.get')}}">
                            @csrf
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary jg-color-3 border-0">
                                        Stuur wachtwoord reset link
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
