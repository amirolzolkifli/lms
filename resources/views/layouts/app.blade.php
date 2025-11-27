<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Dreams LMS is a powerful Learning Management System template designed for educators, training institutions, and businesses.">
    <meta name="keywords" content="LMS template, Learning Management System, e-learning software, online course platform">
    <meta name="author" content="Dreams Technologies">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('site_name', config('app.name', 'Dreams LMS')) }} - @yield('title', 'Dashboard')</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-icon.png') }}">

    <!-- Theme Settings Js -->
    <script src="{{ asset('assets/js/theme-script.js') }}"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/swiper/css/swiper-bundle.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">

    <!-- Slick CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/slick/slick-theme.css') }}">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/feather/feather.css') }}">

    <!-- Aos CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/aos/aos.css') }}">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.css') }}">

    <!-- Iconsax CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/iconsax.css') }}">

    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fancybox/jquery.fancybox.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <!-- Simple Mobile Menu CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/simple-mobile-menu.css') }}">

    @stack('styles')

</head>
<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Header (same as welcome page) -->
        @include('layouts.partials.welcome-header')
        <!-- /Header -->

        <!-- Dashboard Banner Section (like welcome banner) -->
        <section class="dashboard-banner-section">
            <div class="container">
                @yield('content')
            </div>
        </section>

        <!-- Footer -->
        @include('layouts.partials.footer')
        <!-- /Footer -->

    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Select2 JS -->
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <!-- Swiper JS -->
    <script src="{{ asset('assets/plugins/swiper/js/swiper-bundle.min.js') }}"></script>

    <!-- Slick Slider -->
    <script src="{{ asset('assets/plugins/slick/slick.js') }}"></script>

    <!-- Aos JS -->
    <script src="{{ asset('assets/plugins/aos/aos.js') }}"></script>

    <!-- Counter JS -->
    <script src="{{ asset('assets/js/counter.js') }}"></script>

    <!-- Fancybox JS -->
    <script src="{{ asset('assets/plugins/fancybox/jquery.fancybox.min.js') }}"></script>

    <!-- Circle Progress JS -->
    <script src="{{ asset('assets/js/circle-progress.min.js') }}"></script>

    <!-- Theia Sticky Sidebar JS -->
    <script src="{{ asset('assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/js/script.js') }}"></script>

    <!-- Simple Mobile Menu JS -->
    <script src="{{ asset('assets/js/simple-mobile-menu.js') }}"></script>

    @stack('scripts')

</body>
</html>
