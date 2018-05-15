<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="assets/global/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/css/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/bootstrap/css/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/bootstrap/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="assets/global/bootstrap/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css" /> -->
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/css/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/css/owl.theme.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/plugins/slider-revolution-slider/rs-plugin/css/settings.css" rel="stylesheet">
    <link href="assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/css/header.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <link href="assets/one-page/css/settings.css" rel="stylesheet">
    <link href="assets/one-page/css/style.css" rel="stylesheet" type="text/css" />
    <link href="assets/one-page/css/red.css" rel="stylesheet" type="text/css" />
    <link href="assets/one-page/css/blue.css" rel="stylesheet" type="text/css" />
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="assets/global/css/layout.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="assets/global/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" /> -->
    <link href="assets/global/css/login.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico" />
    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/custom-style.css" rel="stylesheet">
    <link href="/css/app.custom.css" rel="stylesheet">
    <link href="/app_/css/chat.css" rel="stylesheet">    
    <link href="assets/personal-account/css/sidebar.css" rel="stylesheet">
    @yield('additional_styles')
    <!-- Bootstrap -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app">
        <div class="header" >
            <div class="container-fluid">
                <a class="mobi-toggler"><i class="fa fa-bars"></i></a>
                @if (!Auth::guest())
                <div class="row">
                    <div class="desktop-menu">
                        <div class="logo-mid col-sm-3">
                            <a class="" href="/#/home"><img src="assets/img/logo.png" alt="MillionMitzvot.com" class="logo-mid"></a>
                        </div>
                        <div class="col-sm-9" style="height: 75px;">
                            <div class="navbar">
                                <div class="navbar-collapse">
                                    <ul class="nav navbar-nav" id="header-ul">
                                        @if (!Auth::guest())
                                        <li class="main-menu-li">
                                            <a href="/#/home">
                                                Home
                                            </a>
                                        </li>
                                        <li><a href="/#/director_board/">Board of Directors</a></li>
                                        <li  class="main-menu-li">
                                            <a class="" href="/#/score">
                                                ScoreBoard
                                            </a>
                                        </li>
                                        <li class="main-menu-li">
                                            <a class="" href="/#/sell">
                                                Purchase
                                            </a>
                                        </li>
                                        <li class="nav-dropdown">
                                            <a href="/#/selldonate">Donate <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="/#/selldonate">Donate</a></li>
                                                <li class="active"><a href="/#/profile/donate">Ask Donation</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="/#/contact/">Contact Us</a></li>
                                        <li class="nav-dropdown">
                                            <a href="/#/sponsor">Sponsor <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="/#/sponsor">Become a Sponsor</a></li>
                                                <li class="active"><a href="/#/sponsor/list">Our Sponsors</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="/#/faq">FAQ</a></li>
                                        @endif
                                        @if(!Auth::guest() && Auth::user()->hasRole('ADMIN'))
                                        <li class="main-menu-li">
                                            <a class="" href="/admin">
                                                Admin
                                            </a>
                                        </li>
                                        @endif
                                        @if(!Auth::guest() && Auth::user()->hasRole('DROP_SHIPPER'))
                                        <li class="main-menu-li">
                                            <a class="" href="/shipper">
                                                Ship
                                            </a>
                                        </li>
                                        @endif
                                        <li class="notification">
                                            <a href="#" class="relative"><img src="assets/img/notification-icon.png" class="img-respons" alt="notification"> <span class="badge">3</span></a>                         
                                        </li>
                                        <li class="nav-dropdown relative">
                                            <a class="dropdown-toggle" data-toggle="dropdown">
                                                @if (Auth::guest())
                                                    Sign In/Up
                                                @else
                                                <a  class="account-btn"><img src="{{Auth::user()->image_url}}" class="img-responsive" alt="User"> {{$user->name}} <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                                                @endif
                                            </a>
                                            <ul class="dropdown-menu">
                                                <div class="drop-head">
                                                        <span> YOUR ACCOUNT</span>
                                                        <a class="close-btn" id="close-btn">X</a>
                                                </div>
                                                @if (Auth::guest())
                                                    <li class="menu-li-app"><a href="{{ url('/login') }}">Sign In</a></li>
                                                    <li class="menu-li-app"><a href="{{ url('/register') }}">Sign Up</a></li>
                                                @else
                                                    <li class="menu-li-app"><a href="/#/profile/">Edit Profile</a></li>
                                                    <li class="menu-li-app">
                                                        <a href="/messages">Chat History</a>
                                                    </li>
                                                    @if(!Auth::user()->hasRole('INDIVIDUAL') && !Auth::user()->hasRole('MEMBER'))
                                                    <li class="menu-li-app" >
                                                        <a class="" href="/#/report">
                                                            Payment History
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @if(!Auth::user()->hasRole('MEMBER'))
                                                        <li class="menu-li-app"><a href="/#/profile/boxes">My Boxes</a></li>
                                                        <li class="menu-li-app"><a href="/#/profile/sounds">My Box Sounds</a></li>
                                                    @endif
                                                    @if(!Auth::user()->hasRole('INDIVIDUAL') && !Auth::user()->hasRole('MEMBER'))
                                                        <li class="menu-li-app"><a href="/#/profile/members">My Members</a></li>
                                                    @endif
                                                    <li class="menu-li-app"><a href="/#/profile/changePassword">Change Password</a></li>
                                                    <li class="menu-li-app">
                                                        <a href="{{ url('/logout') }}"
                                                            onclick="event.preventDefault();
                                                                    document.getElementById('logout-form').submit();">
                                                            Logout
                                                        </a>

                                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                                            {{ csrf_field() }}
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </li>                                       
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @if (Session::has('flash_message'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                {{ Session::get('flash_message') }}
                            </div>
                        @endif
                    </div>

                    <div class="mobile-menu">
                        <div class="col-xs-8">
                            <div class="logo">
                                <a href="/#/home"><img src="assets/img/logo.png" class="img-responsive" alt="Logo"></a>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="menu">
                                <a id="menu-btn"><img src="assets/img/menu-bar.png" class="img-responsive" ></a>
                            </div>
                        </div>
                        <nav class="after-login" id="mobile-side-menu" hidden>
                            <div class="transparent"></div>
                            <!-- <div class="close-btn"><a (click)="showMobileMenu(true)">X</a></div> -->
                            <div id="sidebar" >
                                <div class="back">
                                    <a id="back-btn">< Back to website</a>
                                </div>
                                <div class="user-details">
                                    <span class="user-img"><img src="{{$user->image_url}}" class="img-responsive"></span>
                                    <p>{{$user->name}}</p>
                                </div>
                                <ul class="drop-body">
                                    <li><a href="/#/profile/">Edit Profile</a></li>
                                    <li><a href="/messages">Chat History</a></li>
                                    <li>
                                        <a class="" href="/#/report">
                                            Payment History
                                        </a>
                                    </li>
                                    <li ><a href="/#/profile/boxes">My Boxes</a></li>
                                    <li ><a href="/#/profile/sounds">My Box Sounds</a></li>
                                    <li ><a href="/#profile/members">My Members</a></li>
                                    <li><a href="/#/profile/changePassword">Change Password</a></li>
                                    <li><a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li> 
                                </ul>                                  
                                <ul>                   
                                    <li class="">
                                        <a href="/#/director_board/" >
                                            About
                                        </a>   
                                    </li>
                                    <li  class="" >
                                        <a class="" href="/#/score">
                                            Scoreboard
                                        </a>
                                    </li>     
                                    <li class="" >
                                        <a class="" href="/#/sell">
                                            Donate
                                        </a>
                                    </li>
                                    <li><a href="/#/selldonate">Donate Ask</a></li>
                                    <li><a href="/#/contact/">Contack Us</a></li>
                                    <li><a href="/#/sponsor">Sponsor</a></li>                    
                                    <li><a href="/#/faq">FAQ</a></li>
                                    <li class="main-menu-li">
                                        <a class="" href="/admin">
                                            Admin
                                        </a>
                                    </li>                          
                                </ul>                   
                            </div>
                        </nav>
                    </div>                    
                </div>
                @endif
            </div>
        </div>
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="assets/global/js/jquery.min.js"></script>
    <script src="assets/global/js/jquery-migrate.min.js" type="text/javascript"></script>
    <script src="assets/global/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/global/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="assets/global/js/owl.carousel.min.js" type="text/javascript"></script>
    <script src="assets/global/js/bootstrap-fileinput.js" type="text/javascript"></script>
    <!-- <script src="/js/app.js"></script> -->
    <script src="/js/custom.js"></script>

    @yield('script')

</body>
</html>
