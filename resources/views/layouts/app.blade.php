<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('settings.SITE_NAME', 'Admin - Livestock') }}</title>

    <!-- Common Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Custom Styles -->
@yield('css')

<!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <script>
        let dateFormat = '{{ \App\Helpers\CommonHelper::getJsDisplayDateFormat() }}';
    </script>

</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper" id="app">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="{{ url('/') }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>{{ config('settings.SITE_SHORT_NAME') }}</b></span>

            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg pull-left">
                <img src="{{ asset('storage/sites/' . config('settings.SITE_LOGO')) }}" alt="Site Logo"
                class="logo-img">
                <b>Admin</b>{{ config('settings.SITE_SHORT_NAME') }}
            </span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">

            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <!-- Nav bar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the nav bar-->
                            <img
                                src="{{ asset('storage/profiles/' . optional(optional(Auth::user())->uploadedFile)->filename) }}"
                                class="user-image" alt="User Image">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ optional(Auth::user())->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img
                                    src="{{ asset('storage/profiles/' . optional(optional(Auth::user())->uploadedFile)->filename) }}"
                                    class="img-circle" alt="User Image">

                                <p>
                                    {{ optional(Auth::user())->name }}
                                    - {{ optional(Auth::user())->roles()->first()->name }}
                                    <small>Member since {{ optional(Auth::user())->created_at }}</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ route('users.show', optional(Auth::user())->id ) }}"
                                       class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        Sign out
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li {{ (Request::is('settings*') ? 'class=active' : '') }}>
                        <a href="{{ route('settings.all') }}"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- search form (Optional) -->
            <form method="get" class="sidebar-form" id="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" autocomplete="off" class="form-control" aria-label="search"
                           placeholder="Search..." id="search-input">
                    <span class="input-group-btn">
                        <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </form>
            <!-- /.search form -->

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                @if (App\Helpers\CommonHelper::isCapable('dashboard.index'))
                    <li {{ (Request::is('home') || Request::is('/')? 'class=active' : '') }}>
                        <a href="{{ url('/home') }}">
                            <i class="fa fa-dashboard"></i> <span>{{__('info.dashboard')}}</span>
                        </a>
                    </li>
                @endif

                @if (App\Helpers\CommonHelper::isCapable([
                    'livestocks.index',
                    'livestocks_types.index'
                ]))
                    <li class="{{ ((Request::is('livestocks*')
                                 || Request::is('livestocks_types*')
                               ) ? 'active treeview menu-open' : 'treeview') }} ">
                        <a href="#">
                            <i class="fa fa-list"></i>
                            <span>{{__('info.livestock_management')}}</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            @if (App\Helpers\CommonHelper::isCapable('livestocks.index'))
                                <li {{ (Request::routeIs('livestocks.*') ? 'class=active' : '') }}>
                                    <a href="{{ url('/livestocks') }}">
                                        <i class="fa fa-hdd-o"></i> {{__('info.livestocks')}}
                                    </a>
                                </li>
                            @endif

                             @if (App\Helpers\CommonHelper::isCapable('livestocks_types.index'))
                                <li {{ (Request::routeIs('livestocks_types.*') ? 'class=active' : '') }}>
                                    <a href="{{ url('/livestocks_types') }}">
                                        <i class="fa fa-th-large"></i> {{__('info.livestock_type')}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                
                @if (App\Helpers\CommonHelper::isCapable('sheds.index'))
                    <li {{ (Request::routeIs('sheds.*') ? 'class=active' : '') }}>
                        <a href="{{ route('sheds.index') }}">
                            <i class="fa fa-home"></i> <span>{{__('info.sheds')}}</span>
                        </a>
                    </li>
                @endif
                @if (App\Helpers\CommonHelper::isCapable('foodHistory.index'))
                    <li {{ (Request::routeIs('foodHistory.*') ? 'class=active' : '') }}>
                        <a href="{{ route('foodHistory.index') }}">
                            <i class="fa fa-sitemap"></i> <span>{{__('info.food_history')}}</span>
                        </a>
                    </li>
                @endif
                @if (App\Helpers\CommonHelper::isCapable('medicines.index'))
                    <li {{ (Request::routeIs('medicines.*') ? 'class=active' : '') }}>
                        <a href="{{ route('medicines.index') }}">
                            <i class="fa fa-medkit"></i> <span>{{__('info.medicines')}}</span>
                        </a>
                    </li>
                @endif
                @if (App\Helpers\CommonHelper::isCapable('ledgers.index'))
                    <li {{ (Request::is('ledgers*') ? 'class=active' : '') }}>
                        <a href="{{ url('/ledgers') }}">
                            <i class="fa fa-columns"></i> <span>{{__('info.ledgers')}}</span>
                        </a>
                    </li>
                @endif

                @if (App\Helpers\CommonHelper::isCapable('tags.index'))
                    <li {{ (Request::is('tags*') ? 'class=active' : '') }}>
                        <a href="{{ url('/tags') }}">
                            <i class="fa fa-tag"></i> <span>{{__('info.tags')}}</span>
                        </a>
                    </li>
                @endif

                @if (App\Helpers\CommonHelper::isCapable([
                    'leaves.index',
                    'salaries.index',
                    'daily_wages.index'
                ]))
                    <li class="{{ ((Request::is('leaves*')
                                || Request::is('salaries*')
                                || Request::is('daily_wages*')
                               ) ? 'active treeview menu-open' : 'treeview') }} ">
                        <a href="#">
                            <i class="fa fa-users"></i>
                            <span>{{__('info.employees')}}</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            @if (App\Helpers\CommonHelper::isCapable('leaves.index'))
                                <li {{ (Request::is('leaves*') ? 'class=active' : '') }}>
                                    <a href="{{ url('/leaves') }}">
                                        <i class="fa fa-calendar-plus-o"></i> {{__('info.leaves')}}
                                    </a>
                                </li>
                            @endif

                            @if (App\Helpers\CommonHelper::isCapable('salaries.index'))
                                <li {{ (Request::is('salaries*') ? 'class=active' : '') }}>
                                    <a href="{{ url('/salaries') }}">
                                        <i class="fa fa-money"></i> {{__('info.salaries')}}
                                    </a>
                                </li>
                            @endif

                            @if (App\Helpers\CommonHelper::isCapable('daily_wages.index'))
                                <li {{ (Request::is('daily_wages*') ? 'class=active' : '') }}>
                                    <a href="{{ url('/daily_wages') }}">
                                        <i class="fa fa-gg"></i> {{__('info.daily_wages')}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (App\Helpers\CommonHelper::isCapable([
                    'inventories.index',
                    'inventory_types.index',
                    'inventory_units.index',
                    'inventory_stocks.index'
                ]))
                    <li class="{{ ((Request::is('inventories*')
                                || Request::is('inventory_types*')
                                || Request::is('inventory_units*')
                                || Request::is('inventory_stocks*')
                               ) ? 'active treeview menu-open' : 'treeview') }} ">
                        <a href="#">
                            <i class="fa fa-server"></i>
                            <span>{{__('info.inventory_management')}}</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            @if (App\Helpers\CommonHelper::isCapable('inventories.index'))
                                <li {{ (Request::is('inventories*') ? 'class=active' : '') }}>
                                    <a href="{{ url('/inventories') }}">
                                        <i class="fa fa-cube"></i> {{__('info.inventories')}}
                                    </a>
                                </li>
                            @endif

                            @if (App\Helpers\CommonHelper::isCapable('inventory_stocks.index'))
                                <li {{ (Request::is('inventory_stocks*') ? 'class=active' : '') }}>
                                    <a href="{{ url('/inventory_stocks') }}">
                                        <i class="fa fa-cubes"></i> {{__('info.inventory_stocks')}}
                                    </a>
                                </li>
                            @endif

                            @if (App\Helpers\CommonHelper::isCapable('inventory_types.index'))
                                <li {{ (Request::is('inventory_types*') ? 'class=active' : '') }}>
                                    <a href="{{ url('/inventory_types') }}">
                                        <i class="fa fa-th-large"></i> {{__('info.inventory_types')}}
                                    </a>
                                </li>
                            @endif

                            @if (App\Helpers\CommonHelper::isCapable('inventory_units.index'))
                                <li {{ (Request::is('inventory_units*') ? 'class=active' : '') }}>
                                    <a href="{{ url('/inventory_units') }}">
                                        <i class="fa fa-sitemap"></i> {{__('info.inventory_units')}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (App\Helpers\CommonHelper::isCapable([
                    'modules.index',
                    'roles.index',
                    'permissions.index',
                    'users.index',
                    'event_logs.index'
                ]))
                    <li class="{{ ((Request::is('modules*')
                                || Request::is('roles*')
                                || Request::is('permissions*')
                                || Request::is('users*')
                                || Request::is('event_logs*')
                               ) ? 'active treeview menu-open' : 'treeview') }} ">
                        <a href="#">
                            <i class="fa fa-shield"></i>
                            <span>{{__('info.admin_privilege')}}</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            @if (App\Helpers\CommonHelper::isCapable('modules.index'))
                                <li {{ (Request::is('modules*') ? 'class=active' : '') }}>
                                    <a href="{{ url('/modules') }}">
                                        <i class="fa fa-suitcase"></i> {{__('info.modules')}}
                                    </a>
                                </li>
                            @endif

                            @if (App\Helpers\CommonHelper::isCapable('roles.index'))
                                <li {{ (Request::is('roles*') ? 'class=active' : '') }}>
                                    <a href="{{ url('/roles') }}">
                                        <i class="fa fa-user-secret"></i> {{__('info.roles')}}
                                    </a>
                                </li>
                            @endif

                            @if (App\Helpers\CommonHelper::isCapable('permissions.index'))
                                <li {{ (Request::is('permissions*') ? 'class=active' : '') }}>
                                    <a href="{{ url('/permissions') }}">
                                        <i class="fa fa-key"></i> {{__('info.permissions')}}
                                    </a>
                                </li>
                            @endif

                            @if (App\Helpers\CommonHelper::isCapable('users.index'))
                                <li {{ (Request::is('users*') ? 'class=active' : '') }}>
                                    <a href="{{ url('/users') }}">
                                        <i class="fa fa-user"></i> {{__('info.users')}}
                                    </a>
                                </li>
                            @endif

                            @if (App\Helpers\CommonHelper::isCapable('event_logs.index'))
                                <li {{ (Request::is('event_logs*') ? 'class=active' : '') }}>
                                    <a href="{{ url('/event_logs') }}">
                                        <i class="fa fa-file-archive-o"></i> {{__('info.event_logs')}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (App\Helpers\CommonHelper::isCapable('countries.index'))
                    <li {{ (Request::is('countries*') ? 'class=active' : '') }}>
                        <a href="{{ url('/countries') }}">
                            <i class="fa fa-globe"></i> <span>{{__('info.countries')}}</span>
                        </a>
                    </li>
                @endif

                @if (App\Helpers\CommonHelper::isCapable('settings.index'))
                    <li {{ (Request::is('settings*') ? 'class=active' : '') }}>
                        <a href="{{ url('/settings/all') }}">
                            <i class="fa fa-cogs"></i> <span>{{__('info.settings')}}</span>
                        </a>
                    </li>
                @endif

            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @yield('content-header')
        </section>

        <!-- Main content -->
        <section class="content container-fluid">
            @yield('content')
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            {{ config('settings.VERSION') }}
        </div>
        <!-- Default to the left -->
        {{ config('settings.FOOTER_TEXT') }}
    </footer>

</div>
<!-- ./wrapper -->

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@yield('javascript')

<script src="{{ asset('moment/min/moment.min.js') }}"></script>
<script src="{{ asset('bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>

<script>
    let loaderImageHtml = '<img src="{{ asset('images/loader-64.gif') }}" class="ajax-loader">';

    $(function () {
        "use strict";
        $('#sidebar-form').on('submit', function (e) {
            e.preventDefault();
        });

        $('.sidebar-menu li.active').data('lte.pushmenu.active', true);

        let sidebarMenuLiObj = $('.sidebar-menu li');

        $('#search-input').on('keyup', function () {
            let term = $('#search-input').val().trim();
            if (term.length === 0) {
                sidebarMenuLiObj.each(function () {
                    $(this).show(0);
                    $(this).removeClass('active');
                    if ($(this).data('lte.pushmenu.active')) {
                        $(this).addClass('active');
                    }
                });
                return;
            }

            sidebarMenuLiObj.each(function () {
                if ($(this).text().toLowerCase().indexOf(term.toLowerCase()) === -1) {
                    $(this).hide(0);
                    $(this).removeClass('pushmenu-search-found', false);

                    if ($(this).is('.treeview')) {
                        $(this).removeClass('active');
                    }
                } else {
                    $(this).show(0);
                    $(this).addClass('pushmenu-search-found');

                    if ($(this).is('.treeview')) {
                        $(this).addClass('active');
                    }

                    let parent = $(this).parents('li').first();
                    if (parent.is('.treeview')) {
                        parent.show(0);
                    }
                }

                if ($(this).is('.header')) {
                    $(this).show();
                }
            });

            $('.sidebar-menu li.pushmenu-search-found.treeview').each(function () {
                $(this).find('.pushmenu-search-found').show(0);
            });
        });
    });
</script>

</body>
</html>
