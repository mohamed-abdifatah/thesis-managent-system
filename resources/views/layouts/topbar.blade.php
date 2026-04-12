@php
    $roleName = Auth::user()->role->name ?? 'User';
@endphp

<header class="nxl-header">
    <div class="header-wrapper">
        <div class="header-left d-flex align-items-center gap-3">
            <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </a>
            <div class="nxl-navigation-toggle">
                <a href="javascript:void(0);" id="menu-mini-button">
                    <i class="feather-align-left"></i>
                </a>
                <a href="javascript:void(0);" id="menu-expend-button" style="display: none">
                    <i class="feather-arrow-right"></i>
                </a>
            </div>
            
            <div class="nxl-lavel-mega-menu-toggle d-flex d-lg-none">
                <a href="javascript:void(0);" id="nxl-lavel-mega-menu-open">
                    <i class="feather-grid"></i>
                </a>
            </div>

            <div class="ta-search-wrap d-none d-lg-flex">
                <form action="{{ route('dashboard') }}" method="GET" onsubmit="event.preventDefault();">
                    <i class="feather-search ta-search-icon"></i>
                    <input
                        type="search"
                        class="ta-search-input"
                        placeholder="Search users, theses, proposals..."
                        aria-label="Search dashboard"
                    >
                    <span class="ta-search-shortcut">Ctrl K</span>
                </form>
            </div>
        </div>

        <div class="header-right ms-auto">
            <div class="d-flex align-items-center gap-2">
                <div class="dropdown nxl-h-item d-none d-md-flex">
                    <button type="button" class="ta-head-btn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="feather-menu"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end ta-notification-menu">
                        <h6 class="dropdown-header">Quick Menu</h6>
                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                            <i class="feather-grid"></i>
                            Dashboard
                        </a>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="feather-user"></i>
                            My Profile
                        </a>
                        <a class="dropdown-item" href="{{ route('defense.schedule') }}">
                            <i class="feather-calendar"></i>
                            Defense Schedule
                        </a>

                        @if(auth()->user()->hasRole(['admin', 'coordinator']))
                            <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                <i class="feather-users"></i>
                                User Management
                            </a>
                        @endif

                        @if(auth()->user()->hasRole('student'))
                            <a class="dropdown-item" href="{{ route('proposals.index') }}">
                                <i class="feather-file-text"></i>
                                My Proposal
                            </a>
                        @endif

                        @if(auth()->user()->hasRole(['supervisor', 'cosupervisor']))
                            <a class="dropdown-item" href="{{ route('supervisor.students.index') }}">
                                <i class="feather-users"></i>
                                My Students
                            </a>
                        @endif

                        @if(auth()->user()->hasRole('examiner'))
                            <a class="dropdown-item" href="{{ route('examiner.defenses.index') }}">
                                <i class="feather-check-square"></i>
                                My Evaluations
                            </a>
                        @endif
                    </div>
                </div>

                <div class="dropdown nxl-h-item">
                    <button type="button" class="ta-head-btn position-relative" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="feather-bell"></i>
                        <span class="ta-notification-dot"></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end ta-notification-menu">
                        <h6 class="dropdown-header">Notifications</h6>
                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                            <i class="feather-info"></i>
                            No urgent alerts right now.
                        </a>
                        <a class="dropdown-item" href="{{ route('defense.schedule') }}">
                            <i class="feather-clock"></i>
                            Review upcoming defense sessions.
                        </a>
                    </div>
                </div>

                <div class="nxl-h-item d-none d-sm-flex">
                    <div class="full-screen-switcher">
                        <button type="button" class="ta-head-btn" onclick="toggleFullScreen(document.body)">
                            <i class="feather-maximize maximize"></i>
                            <i class="feather-minimize minimize"></i>
                        </button>
                    </div>
                </div>

                <div class="nxl-h-item dark-light-theme">
                    <button type="button" class="ta-head-btn dark-button">
                        <i class="feather-moon"></i>
                    </button>
                    <button type="button" class="ta-head-btn light-button" style="display: none">
                        <i class="feather-sun"></i>
                    </button>
                </div>

                <div class="dropdown nxl-h-item">
                    <button type="button" class="ta-avatar-trigger" data-bs-toggle="dropdown" data-bs-display="static" data-bs-auto-close="outside" aria-label="Open account menu">
                        <img src="{{ asset('assets/images/avatar/1.png') }}" alt="user-image" class="img-fluid user-avtar me-0" />
                    </button>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('assets/images/avatar/1.png') }}" alt="user-image" class="img-fluid user-avtar" />
                                <div>
                                    <h6 class="text-dark mb-0">{{ Auth::user()->name }} <span class="badge bg-soft-primary text-primary ms-1">{{ ucfirst($roleName) }}</span></h6>
                                    <span class="fs-12 fw-medium text-muted">{{ Auth::user()->email }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i class="feather-user"></i>
                            <span>Profile Details</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="feather-log-out"></i>
                                <span>Logout</span>
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
