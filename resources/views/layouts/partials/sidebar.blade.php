<div class="settings-sidebar bg-light p-3 rounded">
    <div>
        @if(in_array('admin', Auth::user()->roles ?? []))
        <h6 class="mb-3">Admin Section</h6>
        <ul class="mb-3 pb-1">
            <li>
                <a href="{{ route('dashboard') }}" class="d-inline-flex align-items-center {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="isax isax-grid-35 me-2"></i>Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="d-inline-flex align-items-center">
                    <i class="isax isax-teacher me-2"></i>Manage Trainer
                </a>
            </li>
            <li>
                <a href="#" class="d-inline-flex align-items-center">
                    <i class="isax isax-book-15 me-2"></i>Manage Courses
                </a>
            </li>
            <li>
                <a href="#" class="d-inline-flex align-items-center">
                    <i class="isax isax-people me-2"></i>Manage Student
                </a>
            </li>
            <li>
                <a href="{{ route('settings.index') }}" class="d-inline-flex align-items-center {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="isax isax-setting-2 me-2"></i>Settings
                </a>
            </li>
        </ul>
        @elseif(in_array('trainer', Auth::user()->roles ?? []))
        <h6 class="mb-3">Trainer Menu</h6>
        <ul class="mb-3 pb-1">
            <li>
                <a href="{{ route('dashboard') }}" class="d-inline-flex align-items-center {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="isax isax-grid-35 me-2"></i>Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('courses.index') }}" class="d-inline-flex align-items-center {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                    <i class="isax isax-book-15 me-2"></i>My Courses
                </a>
            </li>
            <li>
                <a href="{{ route('profile.edit') }}" class="d-inline-flex align-items-center {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user me-2"></i>My Profile
                </a>
            </li>
        </ul>
        @else
        <h6 class="mb-3">Main Menu</h6>
        <ul class="mb-3 pb-1">
            <li>
                <a href="{{ route('dashboard') }}" class="d-inline-flex align-items-center {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="isax isax-grid-35 me-2"></i>Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('profile.edit') }}" class="d-inline-flex align-items-center {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user me-2"></i>My Profile
                </a>
            </li>
        </ul>
        @endif
        <h6 class="mb-3">Account Settings</h6>
        <ul>
            <li>
                <a href="{{ route('profile.edit') }}" class="d-inline-flex align-items-center {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="isax isax-setting-2 me-2"></i>Settings
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="d-inline-flex align-items-center border-0 bg-transparent w-100 text-start p-0">
                        <i class="isax isax-logout me-2"></i>Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
