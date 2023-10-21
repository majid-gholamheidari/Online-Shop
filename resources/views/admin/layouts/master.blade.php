<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'پنل مدیریت')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- CSRF TOKEN -->
    <meta content="{{ CSRF_TOKEN() }}" name="csrf">
    <!-- Bootstrap 3.3.4 -->
    <link rel="stylesheet" href="{{ asset('assets/panel/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/panel/dist/css/AdminLTE.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/panel/dist/css/skins/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/dist/css/bootstrap-rtl.min.css') }}">

    <!-- Notify -->
    <link rel="stylesheet" href="{{ asset('assets/css/notify.css') }}">
    <!-- Font -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/font.css') }}">
    <style>
        *:not(i, span, .sidebar-toggle) {
            font-family: IRANSans, serif !important;
        }

        .error-span {
            color: #dd4b39;
        }

        textarea {
            resize: none;
        }
    </style>

    @yield('head')
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="/" class="logo">
            <img height="45" src="{{ asset('assets/panel/dist/img/v-logo.png') }}" alt="{{ env('FA_APP_NAME') }}">
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset('assets/panel/dist/img/user-avatar.jpg') }}" class="user-image"
                                 alt="User Image">
                            <span class="hidden-xs">{{ auth()->user()->full_name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="{{ asset('assets/panel/dist/img/user-avatar.jpg') }}" class="img-circle"
                                     alt="User Image">
                                <p>
                                    {{ auth()->user()->full_name }}
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">پروفایل</a>
                                </div>
                                <div class="pull-right">
                                    <a href="#!" onclick="document.getElementById('logout-form').submit()"
                                       class="btn btn-default btn-flat">خروج</a>
                                    <form action="{{ route('logout') }}" method="POST" id="logout-form"
                                          style="display: none;">@csrf</form>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- Sidebar user panel (optional) -->
            <div class="user-panel text-center">
                <div class="image">
                    <img src="{{ asset('assets/panel/dist/img/panel-logo.png') }}" alt="User Image">
                    <br>
                    <p class="h3 text-light-blue">{{ auth()->user()->full_name }}</p>
                </div>
            </div>
            @include('admin.layouts.side-bar')
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @yield('title', 'داشبورد')
            </h1>
            @yield('breadcrumb')
            {{--<ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                <li class="active">Here</li>
            </ol>--}}
        </section>

        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section>
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            Anything you want
        </div>
        <strong>Copyright &copy; 2015 <a href="#">Company</a>.</strong> All rights reserved.
    </footer>
</div>


<!-- jQuery 2.1.4 -->
<script src="{{ asset('assets/panel/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
<!-- Bootstrap 3.3.4 -->
<script src="{{ asset('assets/panel/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/panel/dist/js/app.min.js') }}"></script>
<!-- submit form with ajax -->
<script src="{{ asset('assets/js/submit-form-with-ajax.js') }}"></script>
<!-- Notify -->
<script src="{{ asset('assets/js/notify.js') }}"></script>

@yield('js')
</body>
</html>
