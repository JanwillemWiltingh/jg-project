@extends('layouts.guest')
@section('content')
    <img src="{{asset('storage/img/BG.png')}}" style="width: 100%; height: 100%; position: absolute; margin-left: -120px !important;">
    <div class="reset-page fadeInDown">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <x-alert></x-alert>

                <a href="/"><img src="{{asset('storage/img/JG Rooster v2.png')}}" alt="JG planning"></a>

            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Reset wachtwoord</div>
                    <div class="card-body">
                        <form method="POST" action="{{route('reset.password.post')}}">
                            @csrf
                            <input type="hidden" name="hiddentoken" value="{{ $token }}">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Nieuw wachtwoord</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Voer wachtwoord opnieuw in</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                </div>
                            </div>
                            <div class="col-md-6 offset-md-4">
                                <a href="{{route('forget.password.get')}}">Ga terug</a>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary jg-color-3 border-0">
                                        Reset wachtwoord
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
