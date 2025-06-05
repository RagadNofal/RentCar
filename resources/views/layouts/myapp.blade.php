<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'RentCar') }}</title>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Flatpickr Styles --}}
    @include('flatpickr::components.style')

    {{-- Bootstrap CSS and JS (via Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Bootstrap Icons CDN (needed for icons in footer) --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    />

    <style>
        html {
            scroll-behavior: smooth;
        }
        .animate-btn {
    transition: transform 0.2s ease-in-out;
}
.animate-btn:hover {
    transform: scale(1.05);
}
a span::after {
    content: '';
    display: block;
    margin: auto;
    height: 2px;
    width: 0;
    background: #3B82F6; /* Blue-500 */
    transition: width .3s ease;
}
a:hover span::after {
    width: 100%;
}

    </style>
</head>

<body class="bg-light">


{{-- --------------------------- Guest Header (Bootstrap) --------------------------- --}}


<header>
    <nav class="bg-sec-600 shadow-md backdrop-blur-lg border-b border-gray-200 px-4 lg:px-6 py-4">
        <div class="flex flex-wrap items-center justify-between mx-auto max-w-screen-xl">
            
            {{-- LOGO --}}
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <img src="/images/logos/LOGO.png" class="h-12" alt="Logo" loading="lazy">
            </a>

            {{-- RIGHT SIDE: AUTH --}}
            <div class="flex items-center gap-2 lg:order-2">
                @guest
                    <a href="{{ route('login') }}">
                        <button class="transition-all duration-200 px-4 py-2 text-white bg-gradient-to-br from-blue-500 to-blue-400 hover:scale-105 hover:shadow-md rounded-lg text-sm">
                            Login
                        </button>
                    </a>
                    <a href="{{ route('register') }}">
                        <button class="relative inline-flex items-center justify-center overflow-hidden text-sm font-medium text-white rounded-lg group bg-gradient-to-br from-blue-200 via-blue-300 to-blue-400">
                            <span class="relative px-5 py-2 transition-all ease-in duration-75 bg-blue-600 rounded-md group-hover:bg-transparent group-hover:text-blue-800">
                                Register
                            </span>
                        </button>
                    </a>
                @else
                    {{-- AUTH DROPDOWN --}}
                    <div class="relative">
                        <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                            class="flex items-center gap-2 px-3 py-2.5 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg">
                            <img src="/images/user.png" alt="user" class="w-6 h-6">
                            {{ Auth::user()->role === 'admin' ? 'Admin (' . Auth::user()->name . ')' : Auth::user()->name }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="dropdown"
                            class="z-20 hidden absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 dark:bg-gray-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                                @if(Auth::user()->role === 'admin')
                                    <li>
                                        <a href="{{ route('adminDashboard') }}"
                                            class="block px-4 py-2 hover:bg-blue-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            Dashboard
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-blue-100 dark:hover:bg-gray-600"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endguest

                {{-- Mobile Menu Toggle --}}
                <button data-collapse-toggle="mobile-menu-2" type="button"
                    class="inline-flex items-center p-2 ml-2 text-sm text-white rounded-lg lg:hidden hover:bg-blue-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M3 5h14M3 10h14M3 15h14"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

           {{-- NAVIGATION MENU --}}
@php
    use Illuminate\Support\Str;

    $navItems = [];

    if (auth()->check() && auth()->user()->role === 'admin') {
        $navItems = [
            ['text' => 'Dashboard', 'route' => 'adminDashboard'],
            ['text' => 'Cars', 'route' => 'cars.index'],
            ['text' => 'Users', 'route' => 'users'],
        ];
    } else {
        $navItems = [
            ['text' => 'Home', 'route' => '/'],
            ['text' => 'Cars', 'route' => 'cars'],
            ['text' => 'About Us', 'route' => '/location'],
            ['text' => 'Contact Us', 'route' => '/contact_us'],
        ];

        if (auth()->check()) {
            $navItems[] = ['text' => 'My Reservations', 'route' => 'clientReservation'];
        }
    }
@endphp

<div class="hidden w-full lg:flex lg:items-center lg:w-auto lg:order-1" id="mobile-menu-2">
    <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-10 lg:mt-0 ">
        @foreach ($navItems as $item)
            <li>
                <a href="{{ Str::startsWith($item['route'], '/') ? url($item['route']) : route($item['route']) }}"
                   class="relative text-gray-600 hover:text-blue-500 transition duration-200">
                    <span class="pb-1 border-b-2 border-transparent hover:border-pr-400 transition-all duration-300 ease-in-out">
                        {{ $item['text'] }}
                    </span>
                </a>
            </li>
        @endforeach
    </ul>
</div>

        </div>
    </nav>
</header>









{{-- --------------------------------------------------------------- Main  --------------------------------------------------------------- --}}
<main>
    @yield('content')
</main>

{{-- --------------------------------------------------------------- Footer --------------------------------------------------------------- --}}
@if (Auth::check() && Auth::user()->role === 'admin')
    <footer class="bg-dark text-white py-4 text-center">
        <h2 class="h1 fw-bold m-0">Admin Panel</h2>
    </footer>
@else
    <footer style="background-color: #2e2e2e;" class="text-light pt-5">
        <div class="container">
            <div class="row mb-5 align-items-center">
                <div class="col-md-3 text-center mb-4 mb-md-0">
                    <a href="#">
                        <img
                            src="/images/logos/LOGO.png"
                            alt="Logo"
                            class="img-fluid"
                            style="height: 80px;"
                        />
                    </a>
                </div>

                <div class="col-md-9">
                    <div class="row">
                        <div class="col-6 col-md-4 mb-4 mb-md-0">
                            <h6 class="text-uppercase fw-semibold text-white mb-3">Resources</h6>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="https://laravel.com/" target="_blank" class="text-light text-decoration-none">Laravel 10.x</a>
                                </li>
                                <li>
                                    <a href="https://getbootstrap.com/" target="_blank" class="text-light text-decoration-none">Bootstrap 5</a>
                                </li>
                            </ul>
                        </div>

                        <div class="col-6 col-md-4 mb-4 mb-md-0">
                            <h6 class="text-uppercase fw-semibold text-white mb-3">Follow us</h6>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="https://github.com/RagadNofal/RentCar" target="_blank" class="text-light text-decoration-none">GitHub</a>
                                </li>
                                <li>
                                    <a href="https://www.linkedin.com/in/ragad-nofal/" target="_blank" class="text-light text-decoration-none">LinkedIn</a>
                                </li>
                            </ul>
                        </div>

                        <div class="col-6 col-md-4">
                            <h6 class="text-uppercase fw-semibold text-white mb-3">Legal</h6>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="{{ route('privacy_policy') }}" class="text-light text-decoration-none">Privacy Policy</a>
                                </li>
                                <li>
                                    <a href="{{ route('terms_conditions') }}" class="text-light text-decoration-none">Terms & Conditions</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4 text-light" />

            <div class="d-md-flex justify-content-between align-items-center text-center text-md-start pb-4">
                <span class="small text-light">
                    © {{ now()->year }}
                    <a href="https://www.linkedin.com/in/ragad-nofal/" class="text-decoration-none text-white fw-medium" target="_blank">
                        ragad-nofal
                    </a>
                    . All rights reserved.
                </span>
                <div class="mt-3 mt-md-0">
                    <a href="https://github.com/RagadNofal/RentCar" target="_blank" class="text-light me-3">
                        <i class="bi bi-github fs-5"></i>
                    </a>
                    <a href="https://twitter.com" target="_blank" class="text-light me-3">
                        <i class="bi bi-twitter fs-5"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/ragad-nofal/" target="_blank" class="text-light me-3">
                        <i class="bi bi-linkedin fs-5"></i>
                    </a>
                    <a href="https://www.instagram.com" target="_blank" class="text-light">
                        <i class="bi bi-instagram fs-5"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
@endif

@include('flatpickr::components.script')

<script>
    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: "smooth" });
    }
</script>
</body>
</html>
