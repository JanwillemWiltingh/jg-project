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



<div class="login-page fadeInDown">
    <div class="login-form border shadow">
        <div id="formContent">
            <!-- Tabs Titles -->

            <!-- Icon -->
            <div class="login-icon">
                <i class="fa fa-user"></i>
            </div>

            <!-- Login Form -->
            <form action="{{route('login')}}">

                <div class="login-input">
                    <i class="fa fa-user"></i>
                    <input type="text" id="login" name="login" placeholder="Login">
                </div>
                <div class="login-input">
                    <i class="fa fa-lock"></i>
                    <input type="text" id="password" name="login" placeholder="Password">
                </div>

                <input type="submit" class="" value="Log In">
                <div id="formFooter">
                    <a class="underlineHover" href="#">Forgot Password?</a>
                </div>
            </form>

            <!-- Remind Passowrd -->

        </div>
    </div>
</div>
