<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ App\Helpers\TitleChanger::Title() }}</title>

        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <script type="text/javascript" src="{{asset('/js/app.js')}}"></script>

        {{--Css--}}
        <link rel="stylesheet" href="{{asset('/css/app.css')}}" type="text/css">

        <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />

        <!-- Calender -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"> </script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/lang-all.js"></script>

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{asset('storage/favicon/apple-touch-icon.png')}}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{asset('storage/favicon/favicon-32x32.png')}}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{asset('storage/favicon/favicon-16x16.png')}}">
        <link rel="manifest" href="{{asset('storage/favicon/site.webmanifest')}}">

        <!-- Sweet alert: Has to be above everything I think -->
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

        <!--     Fonts and icons     -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css">

        <!-- Time input -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css" />
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>

        <!-- JQeury coockie -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    </head>
    <body>
    <div class="wrapper @if($browser->isMobile()) nav-container @endif">
            <div class="sidebar @if($browser->isMobile())nav-bar-open @endif"
                 data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
                <div class="logo"><a class="simple-text logo-normal" href="{{route('dashboard.home')}}" style="text-decoration: none;">
                        <img style="margin-top: -20px; margin-bottom: -20px;" src="{{asset('storage/img/JG Rooster v2.png')}}" alt="JG planning">
                    </a></div>
                <div class="sidebar-wrapper" style="@if($browser->isMobile()) height: 80% ; @endif">
                    <ul class="nav">
                        <li class="nav-item active {{ (request()->is('/')) ? 'nav-color-active' : '' }}">
                            <a class="nav-link nav-color" href="{{route('dashboard.home')}}" >
                                <i class="material-icons">dashboard</i>
                                <p>Dashboard</p>
                            </a>
                        </li>
{{--                    Users--}}
                        @can('employee-clock')
                            <li class="nav-item active {{ (request()->is('gebruiker/clock') ?? request()->is('gebruiker/clock/*')) ? 'nav-color-active' : '' }}">
                                <a class="nav-link nav-color" href="{{route('user.clock.index')}}" style="margin-top: 60px;">
                                    <i class="fa fa-clock"></i>
                                    <p>Gewerkte uren</p>
                                </a>
                            </li>
                        @endcan

                        @can('employee-rooster')
                            <li class="nav-item active {{ (request()->is('rooster') or request()->is('rooster/*')) ? 'nav-color-active' : '' }}"  {{--style="position:absolute; left: 22px ;width: 90%"--}}>
                                <a class="nav-link nav-color" href="{{route('rooster.index', ['week' => \Carbon\Carbon::now()->week, 'year' => \Carbon\Carbon::now('Y')->format('Y')])}}" style="margin-top: 120px;">
                                    <i class="fa fa-calendar" style="color: white"></i>
                                    <p style="color: white">Rooster</p>
                                </a>
                            </li>
                        @endcan

                        @if(!$browser->isMobile())
                            @can('admin-users')
                                <li class="nav-item active {{ (request()->is('admin/users')) ? 'nav-color-active' : '' }}">
                                    <a class="nav-link nav-color" href="{{route('admin.users.index')}}" style="margin-top: 60px;">
                                        <i class="fa fa-user"></i>
                                        <p>Gebruikers</p>
                                    </a>
                                </li>
                            @endcan

    {{--                    Admin--}}

                            @can('admin-clocker')
                                <li class="nav-item active {{ (request()->is('admin/clock') or request()->is('admin/clock/*')) ? 'nav-color-active' : '' }}">
                                    <a class="nav-link nav-color" href="{{route('admin.clock.index')}}" style="margin-top: 120px;">
                                        <i class="fa fa-clock"></i>
                                        <p>Klok</p>
                                    </a>
                                </li>
                            @endcan

                            @can('admin-beschikbaarheid')
                                <li class="nav-item active hover-navbar">
                                    <a class="nav-link nav-color" style="margin-top: 180px;">
                                        <i class="fa fa-calendar"></i>
                                        <i class="fa fa-angle-down" style="color: white; font-size: 15px; margin-left: -20px; margin-right: -5px"></i>
                                        <p>Beschikbaarheid beheren</p>
                                    </a>
                                </li>

                                <div class="hover-navbar-content">
                                    <li class="nav-item {{ (request()->is('rooster') or request()->is('rooster')) ? 'nav-color-active' : '' }}"  style="position:absolute; left: 22px;width: 90%">
                                        <a class="nav-link nav-color" href="{{route('admin.rooster.index')}}" style="margin-top: 240px;">
                                            <i class="fa fa-calendar" style="color: white"></i>
                                            <p style="color: white">Rooster</p>
                                        </a>
                                    </li>

{{--                                    <li class="nav-item {{ (request()->is('admin/vergelijken') or request()->is('admin/vergelijken/*')) ? 'nav-color-active' : '' }}"  style="position:absolute; left: 22px;width: 90%">--}}
{{--                                        <a class="nav-link nav-color" href="{{route('admin.compare.index')}}" style="margin-top: 300px;">--}}
{{--                                            <i class="fa fa-calendar" style="color: white"></i>--}}
{{--                                            <p style="color: white">Vergelijken</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
                                </div>
                            @endcan
                        @endif

                        @can('admin-logout')
                            @if($browser->isMobile())
                                <li class="nav-item active hover-navbar">
                                    <a class="nav-link nav-color" data-bs-toggle="modal" data-bs-target="#dropDownMenu" data-backdrop="false" style="position: absolute; bottom: 50px;">
                                        <i class="fa fa-user"></i>
                                        <p>{{\Illuminate\Support\Facades\Auth::user()['firstname']}}</p>
                                    </a>
                                </li>
                            @endif
                        @endcan
                    </ul>
                </div>
            </div>

            <div class="main-panel">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
                    <div class="container-fluid">

                    @if($browser->isMobile())
                        <header>
                            <div class="toggle-btn fadeInDown">
                                <span></span>
                            </div>
                        </header>
                    @endif

                        <div class="collapse navbar-collapse justify-content-end">
                            <ul class="navbar-nav">
{{--                                <li class="nav-item">--}}
{{--                                    <a class="nav-link" href="javascript:;">--}}
{{--                                        <i class="material-icons">dashboard</i>--}}
{{--                                        <p class="d-lg-none d-md-block">--}}
{{--                                            Stats--}}
{{--                                        </p>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
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
                                @if(!$browser->isMobile())
                                    <li class="nav-item dropdown">
                                        <button class="dropdown_button" style="margin: 15px; font-size: 20px;" id="dropdown_button" data-bs-toggle="modal" data-bs-target="#dropDownMenu" data-backdrop="false">
                                            {{\Illuminate\Support\Facades\Auth::user()['firstname']}} <i class="fa fa-user"></i>
                                            <i class="fas fa-caret-down" id="arrow" style="height: 100%"></i>
                                        </button>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </nav>
                <br>
                <br>

                <br>

                <!-- Modal -->
                @if(!$browser->isMobile())
                    <div class="modal fade" id="dropDownMenu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document" style="width: 100px; left: 45%; top: 5%">
                            <div class="modal-content">
                                <div class="modal-body">

                                    <a href="{{route('profile.index')}}">Profiel</a>
                                    <hr>
                                    <a href="{{route('help.index')}}">Help</a> <br>
                                    <hr>
                                    <form action="{{ route('auth.logout') }}" method="POST">
                                        @csrf
                                        <button class="linklike-button">Uitloggen</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="modal fade" id="dropDownMenu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog mobile-user-modal" role="document">
                            <div class="modal-content jg-color-gradient-3">
                                <div class="modal-body">

                                    <a href="{{route('profile.index')}}">
                                        <i class="fa fa-user"></i>
                                        Profiel
                                    </a>  <br>
                                    <hr>
                                    <a href="{{route('help.index')}}">
                                        <i class="fa fa-book-open"></i>
                                        Help
                                    </a> <br>
                                    <hr>
                                    <form action="{{ route('auth.logout') }}" method="POST">

                                        @csrf
                                        <button class="linklike-button"><i class="fa fa-sign-out-alt"></i> Logout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="container" style="width: 100%; @if(!$browser->isMobile()) height: 80% !important; @endif">
                    <x-alert></x-alert>

                    @yield('content')
                    <!-- Sweet Alert -->
                    @if(!session()->has('first_time_session')) {{-- TODO: Betere manier vinden om dit uit te voeren --}}
                    @if(\Illuminate\Support\Facades\Auth::user()->role->name == "admin" || \Illuminate\Support\Facades\Auth::user()->role->name == "maintainer" )
                        @if(App\Models\Browser::isMobile())
                            <script>
                                swal({
                                    title: "Kijk Uit!",
                                    text: "Admin functies zijn niet bereikbaar op telefoon, log in op PC om admin functies te gebruiken.",
                                    icon: "warning",
                                });
                            </script>
                        @endif
                    @endif
                        @if(App\Models\Browser::getBrowserName() == 'Firefox')
                            <script>
                                swal({
                                    title: "Pas Op!",
                                    text: "U gebruikt firefox, sommige functionaliteit zullen eranders uitzien dan normaal.",
                                    icon: "warning",
                                });
                            </script>
                        @endif
                        @php session()->put('first_time_session', true) @endphp
                    @endif

                </div>

            </div>
        </div>
        {{--JS--}}

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js"></script>
{{--        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.css"/>--}}
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>


    </body>
</html>
