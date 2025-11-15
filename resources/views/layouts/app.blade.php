<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Page Title -->
    <title>@yield('title', config('app.name'))</title>

    <!-- Favicon -->
    <link href="{{ asset('/svg/dove.svg') }}" rel="icon" type="image/png">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('dashboard/vendor/jquery/dist/jquery-3.4.1.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('dashboard/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('extra-css')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container">
                @if(Request::is('r/*'))
                    <a href="{{ URL::previous() }}" class="back pr-4" style="margin-left: -15px"><i
                            class="fas fa-chevron-circle-left"></i> Quay lại</a>
                @endif
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('/svg/dove.svg') }}" style="height: 30px; width: 30px">
                    Food and Maybe Pigeons?
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Chuyển đổi điều hướng">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li>
                            @if(Session::has('address'))
                                ASAP • {{ strtok(Session::get('address.place_name'), ',') }}
                            @endif
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        @if(Request::is('r/*') && !Request::is('r/*/checkout'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('checkout', $restaurant->slug) }}" title="Giỏ hàng">
                                    <i class="fas fa-shopping-basket"></i>
                                    @if(!\Cart::isEmpty())
                                        <span class="badge badge-info">{{ \Cart::getTotalQuantity() }}</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                                </a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus"></i> Đăng ký
                                    </a>
                                </li>
                            @endif
                        @else
                            @if(auth()->guard('web')->check())
                                <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.orders') }}" title="Đơn hàng của bạn"><i
                                            class="fas fa-receipt"></i></a>
                            </li>
                            @elseif(auth()->guard('driver')->check())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('driver.trips') }}" title="Chuyến đi của bạn"><i
                                            class="fas fa-route"></i></a>
                                </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ auth()->user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#">
                                        Cài đặt
                                    </a>

                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                 document.getElementById('logout-form').submit();">
                                        Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="pb-4">
            @yield('content')
        </main>
        @include('layouts.footer')
    </div>

    @yield('extra-js')
    @stack('scripts')
</body>

</html>