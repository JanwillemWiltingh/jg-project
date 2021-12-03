{{--JS--}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{asset('/js/app.js')}}"></script>

{{--CSS--}}
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="{{asset('/css/app.css')}}" type="text/css">
<link rel="apple-touch-icon" sizes="180x180" href="{{asset('storage/favicon/apple-touch-icon.png')}}">
<link rel="icon" type="image/png" sizes="32x32" href="{{asset('storage/favicon/favicon-32x32.png')}}">
<link rel="icon" type="image/png" sizes="16x16" href="{{asset('storage/favicon/favicon-16x16.png')}}">
<link rel="manifest" href="{{asset('storage/favicon/site.webmanifest')}}">
<style>
    img {
        position: absolute;
    }
</style>
<img src="{{asset('storage/img/BG.png')}}" style="width: 100%; height: 100%">
<x-alert></x-alert>
<div class="login-page fadeInDown">
    <div class="login-form border shadow">
        <!-- Icon -->
        <div class="login-icon">
            <i class="fa fa-user"></i>
        </div>

        <!-- Login Form -->
        <form action="{{route('auth.login')}}" method="POST" style="width: 75%">
            @csrf

            <input type="email" id="login" name="email" placeholder="E-mail" class="form-control" value="{{ old('email') }}">

            <input type="password" id="password" name="password" placeholder="Wachtwoord" class="form-control">

            <input type="submit" class="login-button" value="Inloggen">
            <div id="formFooter" style="text-align: center; margin: 15px;">
                <a class="underlineHover" href="{{route('forget.password.get')}}">Wachtwoord Vergeten?</a>
            </div>
            @error('status')
            <label class="error-label"><strong>{{$errors->first('status')}}</strong></label>
            @enderror
        </form>
    </div>
</div>
