<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Customer Dashboard | House Cleaning Service Management System" />
    <meta name="author" content="" />

    <link rel="icon" href="{{ asset('images/Smarthands.png') }}">
    <title>Smarthands Cleaning Services | Customer Dashboard</title>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-icons/entypo/css/entypo.css') }}">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/neon-core.css') }}">
    <link rel="stylesheet" href="{{ asset('css/neon-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/neon-forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    @stack('styles')
</head>
<body class="page-body page-left-in" data-url="http://neon.dev">

<div class="page-container">
    @include('customer.partials.sidebar')

    <div class="main-content">
        <div class="row">
            <!-- Profile Info and Notifications -->
            <div class="col-md-6 col-sm-8 clearfix">
                <ul class="user-info pull-left pull-none-xsm">
                    <!-- Customer info can be added here -->
                </ul>
            </div>

            <div class="col-md-6 col-sm-4 clearfix hidden-xs">
                <ul class="list-inline links-list pull-right">
                    <li>
                        <a href="{{ route('logout') }}" 
                        onclick="event.preventDefault(); 
                                    if(confirm('Are you sure you want to log out?')) { 
                                        document.getElementById('logout-form').submit(); 
                                    }" 
                        style="color: blue;">
                            Log Out <i class="entypo-logout right"></i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <hr />
        @yield('content')
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/gsap/TweenMax.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.js') }}"></script>
<script src="{{ asset('js/joinable.js') }}"></script>
<script src="{{ asset('js/resizeable.js') }}"></script>
<script src="{{ asset('js/neon-api.js') }}"></script>
</body>
</html>
