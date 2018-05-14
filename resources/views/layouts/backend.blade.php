<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="/css/admin.topbar.css" rel="stylesheet">
        <link href="/css/admin.sidebar.css" rel="stylesheet">
        <link href="/assets/global/css/header.css" rel="stylesheet" type="text/css" />
        <link href="/admin_/css/user-list.css" rel="stylesheet" type="text/css" />
        <link href="/admin_/css/roles.css" rel="stylesheet" type="text/css" />
        <link href="/admin_/css/discounts.css" rel="stylesheet" type="text/css" />
        <link href="/admin_/css/donates.css" rel="stylesheet" type="text/css" />
        <link href="/admin_/css/message_history.css" rel="stylesheet" type="text/css" />
        <link href="/admin_/css/permissions.css" rel="stylesheet" type="text/css" />
        <link href="/admin_/css/posts.css" rel="stylesheet" type="text/css" />
        <link href="/admin_/css/upload_firmware.css" rel="stylesheet" type="text/css" />
        <link href="/admin_/css/upload_policies_rules.css" rel="stylesheet" type="text/css" />
        <link href="/admin_/css/admin.sponsors.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/css/footer.css" rel="stylesheet" type="text/css" />
        <link href="/css/app.backend.custom.css" rel="stylesheet">
        <style type="text/css">
            .donate-image{
                width: 150px;
                height: 150px;
            }
        </style>

        <!-- jquery -->
        <script src="/assets/global/js/jquery.min.js"></script>
        <script src="/assets/global/js/jquery-migrate.min.js" type="text/javascript"></script>

        @yield('styles')
        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
    <body>

        <div class="container-fluid">
            <div class="row">
                <div class="top-bar">
                    <div class="col-md-2">
                        <div class="row">
                            <div class="logo">
                                <a href="{{ url('/') }}"><img src="/assets/img/logo.png" class="img-responsive" alt="logo"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="user-box">
                            <!-- <a href="#">
                                <img src="/assets/img/user-img.jpg" class="img-responsive" alt="User"> {{ Auth::user()->name }}
                                <i class="fa fa-angle-down" aria-hidden="true"></i>                                
                            </a> -->
                            <!-- <img src="/assets/img/user-img.jpg" class="img-responsive" alt="User"> -->
                            <ul class="nav navbar-nav navbar-right" >
                                @if (Auth::guest())
                                    <li><a href="{{ url('/login') }}">Login</a></li>
                                    <li><a href="{{ url('/register') }}">Register</a></li>
                                @else 
                                    <li class="dropdown admin-top-menu">
                                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" >
                                            <img src="/assets/img/user-img.jpg" class="img-responsive" alt="User">
                                            {{ Auth::user()->name }} <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="{{ url('/logout') }}"
                                                    onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                                    Logout
                                                </a>

                                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                                    {{ csrf_field() }}
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (Session::has('flash_message'))
            <div class="container">
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ Session::get('flash_message') }}
                </div>
            </div>
        @endif
        @if (Session::has('warning_flash_message'))
            <div class="container">
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ Session::get('flash_message') }}
                </div>
            </div>
        @endif
        @if (Session::has('failed_flash_message'))
            <div class="container">
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ Session::get('failed_flash_message') }}
                </div>
            </div>
        @endif

        @yield('content')

        <hr/>

        <div class="container text-center">
            Copyright &copy; {{ date('Y') }}, MillionMitzvot.com
            <br/><br/>
        </div>

        <script src="assets/global/js/jquery.min.js"></script>
        <script src="assets/global/js/jquery-migrate.min.js" type="text/javascript"></script>
        <script src="assets/global/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/js/owl.carousel.min.js" type="text/javascript"></script>
        <script src="assets/global/js/bootstrap-fileinput.js" type="text/javascript"></script>
        <script src="/js/custom.js"></script>
        <script src="/js/app.js"></script>

        <script type="text/javascript">
            $(function () {
                // Navigation active
                $('ul.navbar-nav a[href="{{ "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" }}"]').closest('li').addClass('active');
            });
        </script>
        @yield('scripts')
    </body>
</html>

        <!-- <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="{{ url('/admin') }}">Dashboard <span class="sr-only">(current)</span></a></li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">Login</a></li>
                            <li><a href="{{ url('/register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav> -->
