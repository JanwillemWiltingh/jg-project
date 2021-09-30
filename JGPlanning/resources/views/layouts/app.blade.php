<!--
=========================================================
Material Dashboard - v2.1.2
=========================================================

Product Page: https://www.creative-tim.com/product/material-dashboard
Copyright 2020 Creative Tim (https://www.creative-tim.com)
Coded by Creative Tim

=========================================================
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

        {{--Css--}}
        <link rel="stylesheet" href="{{asset('/css/app.css')}}" type="text/css">

        <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css">

    </head>

    <body>
        <div class="wrapper ">
            <div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
                <!--
                  Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

                  Tip 2: you can also add an image using data-image tag
              -->
                <div class="logo"><a class="simple-text logo-normal">
                        JGPlanning
                    </a></div>
                <div class="sidebar-wrapper">
                    <ul class="nav">

                        <li class="nav-item {{ (Request::is('/') or Request::is('dashboard/*')) ? 'active' : '' }}">
                            <a class="nav-link nav-color" href="{{route('dashboard.home')}}">
                                <i class="material-icons">dashboard</i>
                                <p>Dashboard</p>
                            </a>
                            <br>
                        </li>

                        <br>

                        <li class="nav-item {{ (Request::is('users') or Request::is('users/*')) ? 'active' : '' }}">
                            <a class="nav-link nav-color" href="{{route('users.index')}}">
                                <i class="fa fa-clock"></i>
                                <p>Users</p>
                            </a>
                            <br>
                        </li>

                        <br>

                        <li class="nav-item {{ (Request::is('rooster') or Request::is('rooster/*')) ? 'active' : '' }}">
                            <a class="nav-link nav-color" href="{{route('rooster.index')}}">
                                <i class="fa fa-clock"></i>
                                <p>Rooster</p>
                            </a>
                            <br>
                        </li>

                        <br>
                    </ul>
                </div>
            </div>
            <div class="main-panel">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="navbar-toggler-icon icon-bar"></span>
                            <span class="navbar-toggler-icon icon-bar"></span>
                            <span class="navbar-toggler-icon icon-bar"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-end">
{{--                            <form class="navbar-form">--}}
{{--                                <div class="input-group no-border">--}}
{{--                                    <input type="text" value="" class="form-control" placeholder="Search...">--}}
{{--                                    <button type="submit" class="btn btn-white btn-round btn-just-icon">--}}
{{--                                        <i class="material-icons">search</i>--}}
{{--                                        <div class="ripple-container"></div>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </form>--}}
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="javascript:;">
                                        <i class="material-icons">dashboard</i>
                                        <p class="d-lg-none d-md-block">
                                            Stats
                                        </p>
                                    </a>
                                </li>
                                <!--            <li class="nav-item dropdown">
                                              <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="material-icons">notifications</i>
                                                <span class="notification">5</span>
                                                <p class="d-lg-none d-md-block">
                                                  Some Actions
                                                </p>
                                              </a>
                                              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                                <a class="dropdown-item" href="#">Mike John responded to your email</a>
                                                <a class="dropdown-item" href="#">You have 5 new tasks</a>
                                                <a class="dropdown-item" href="#">You're now friend with Andrew</a>
                                                <a class="dropdown-item" href="#">Another Notification</a>
                                                <a class="dropdown-item" href="#">Another One</a>
                                              </div>
                                            </li>-->
                                <li class="nav-item dropdown">
                                    <button class="dropdown_button" style="margin: 15px; font-size: 20px;" id="dropdown_button" data-bs-toggle="modal" data-bs-target="#dropDownMenu" data-backdrop="false">
                                        <i class="fas fa-caret-down" id="arrow" style="height: 100%"></i>
                                        <i class="fa fa-user"></i>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <br>
                <br>
                <br>

                <!-- Modal -->
                <div class="modal fade" id="dropDownMenu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="width: 100px; left: 45%; top: 5%">
                        <div class="modal-content">
                            <div class="modal-body">
                                <form action="{{ route('auth.logout') }}" method="POST">
                                    @csrf
                                    <button class="linklike-button">Logout</button>
                                </form>

                                <hr>

                                <a href="#">Help</a> <br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="col-md-4">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        {{--JS--}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="{{asset('/js/app.js')}}"></script>
    </body>
</html>
