<header class="header-one">
    <div class="container">
        <div class="header-nav">
            <div class="navbar-header">
                <a id="simple_mobile_btn" href="javascript:void(0);">
                    <span class="bar-icon">
                        <i class="isax isax-menu"></i>
                    </span>
                </a>
                <div class="navbar-logo">
                    <a class="logo-white header-logo" href="{{ url('/') }}">
                        <img src="{{ asset('assets/img/logo-white.svg') }}" class="logo" alt="Logo">
                    </a>
                    <a class="logo-dark header-logo" href="{{ url('/') }}">
                        <img src="{{ asset('assets/img/logo-white.svg') }}" class="logo" alt="Logo">
                    </a>
                </div>
            </div>
            <div class="main-menu-wrapper">
                <div class="menu-header">
                    <a href="{{ url('/') }}" class="menu-logo">
                        <img src="{{ asset('assets/img/logo.svg') }}" class="img-fluid" alt="Logo">
                    </a>
                    <a id="menu_close" class="menu-close" href="javascript:void(0);">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
                <ul class="main-nav">
                    <li class="{{ request()->is('/') ? 'active' : '' }}">
                        <a href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="{{ request()->is('about') ? 'active' : '' }}">
                        <a href="{{ url('/about') }}">About</a>
                    </li>
                    <li class="{{ request()->is('courses*') ? 'active' : '' }}">
                        <a href="{{ route('courses.index') }}">Courses</a>
                    </li>
                    <li class="{{ request()->is('trainers') ? 'active' : '' }}">
                        <a href="{{ url('/trainers') }}">Trainer</a>
                    </li>
                    <li class="{{ request()->is('contact') ? 'active' : '' }}">
                        <a href="{{ url('/contact') }}">Contact</a>
                    </li>
                    @auth
                        <li class="{{ request()->is('app*') ? 'active' : '' }}">
                            <a href="{{ route('app.dashboard') }}">Dashboard</a>
                        </li>
                    @endauth
                </ul>
                <div class="menu-login">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">
                            <i class="isax isax-user me-2"></i>Sign In
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-secondary w-100">
                            <i class="isax isax-user-edit me-2"></i>Register
                        </a>
                    @else
                        <a href="{{ route('app.dashboard') }}" class="btn btn-primary w-100 mb-2">
                            <i class="isax isax-grid-35 me-2"></i>Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="isax isax-logout me-2"></i>Logout
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
            <div class="header-btn d-flex align-items-center">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary d-inline-flex align-items-center me-2">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-secondary me-0">
                        Register
                    </a>
                @else
                    <a href="{{ route('app.dashboard') }}" class="btn btn-primary d-inline-flex align-items-center me-2">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-secondary me-0">
                            Logout
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</header>

<!-- Simple Mobile Menu (Only visible on mobile) -->
<div class="simple-mobile-menu" id="simpleMobileMenu">
    <div class="simple-mobile-menu-header">
        <a href="{{ url('/') }}">
            <img src="{{ asset('assets/img/logo-white.svg') }}" alt="Logo" style="height: 35px;">
        </a>
        <button class="simple-mobile-close" id="simpleMobileClose">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <nav class="simple-mobile-nav">
        <ul>
            <li class="{{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}">
                    <i class="isax isax-home me-2"></i>Home
                </a>
            </li>
            <li class="{{ request()->is('about') ? 'active' : '' }}">
                <a href="{{ url('/about') }}">
                    <i class="isax isax-info-circle me-2"></i>About
                </a>
            </li>
            <li class="{{ request()->is('courses*') ? 'active' : '' }}">
                <a href="{{ route('courses.index') }}">
                    <i class="isax isax-book me-2"></i>Courses
                </a>
            </li>
            <li class="{{ request()->is('trainers') ? 'active' : '' }}">
                <a href="{{ url('/trainers') }}">
                    <i class="isax isax-teacher me-2"></i>Trainer
                </a>
            </li>
            <li class="{{ request()->is('contact') ? 'active' : '' }}">
                <a href="{{ url('/contact') }}">
                    <i class="isax isax-call me-2"></i>Contact
                </a>
            </li>
            @auth
                <li class="{{ request()->is('app*') ? 'active' : '' }}">
                    <a href="{{ route('app.dashboard') }}">
                        <i class="isax isax-grid-35 me-2"></i>Dashboard
                    </a>
                </li>
            @endauth
        </ul>
    </nav>

    <div class="simple-mobile-buttons">
        @guest
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="isax isax-user me-2"></i>Sign In
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary">
                <i class="isax isax-user-edit me-2"></i>Register
            </a>
        @else
            <a href="{{ route('app.dashboard') }}" class="btn btn-primary">
                <i class="isax isax-grid-35 me-2"></i>Dashboard
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 mt-2">
                    <i class="isax isax-logout me-2"></i>Logout
                </button>
            </form>
        @endguest
    </div>
</div>

<!-- Mobile Menu Overlay -->
<div class="simple-mobile-overlay" id="simpleMobileOverlay"></div>
