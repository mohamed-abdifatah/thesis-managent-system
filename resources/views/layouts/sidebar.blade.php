@php
    $isDashboard = request()->routeIs('dashboard');
    $isStudentMenu = request()->routeIs('proposals.*') || request()->routeIs('thesis.versions.*') || request()->routeIs('defense.schedule');
    $isSupervisorMenu = request()->routeIs('supervisor.*') || request()->routeIs('defense.schedule');
    $isExaminerMenu = request()->routeIs('examiner.*');
    $isAdminMenu = request()->routeIs('admin.*');
    $isLibrarianMenu = request()->routeIs('profile.*');
@endphp

<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('dashboard') }}" class="b-brand ta-brand">
                <span class="ta-brand-icon">
                    <i class="feather-book-open"></i>
                </span>
                <span class="ta-brand-text">
                    <h4 class="logo logo-lg app-brand-title">Thesis Hub</h4>
                    <small>Management Console</small>
                </span>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Main</label>
                </li>
                <li class="nxl-item">
                    <a href="{{ route('dashboard') }}" class="nxl-link {{ $isDashboard ? 'is-active' : '' }}">
                        <span class="nxl-micon"><i class="feather-grid"></i></span>
                        <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>

                @if(auth()->user() && auth()->user()->hasRole('student'))
                    <li class="nxl-item nxl-hasmenu {{ $isStudentMenu ? 'is-open' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link {{ $isStudentMenu ? 'is-active' : '' }}">
                            <span class="nxl-micon"><i class="feather-file-text"></i></span>
                            <span class="nxl-mtext">My Thesis</span>
                            <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu" @if($isStudentMenu) style="display:block;" @endif>
                            <li class="nxl-item">
                                <a class="nxl-link {{ request()->routeIs('proposals.*') ? 'is-active' : '' }}" href="{{ route('proposals.index') }}">My Proposal</a>
                            </li>
                            <li class="nxl-item">
                                <a class="nxl-link {{ request()->routeIs('thesis.versions.*') ? 'is-active' : '' }}" href="{{ route('thesis.versions.index') }}">Thesis Versions</a>
                            </li>
                            <li class="nxl-item">
                                <a class="nxl-link {{ request()->routeIs('defense.schedule') ? 'is-active' : '' }}" href="{{ route('defense.schedule') }}">Defense Schedule</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(auth()->user() && auth()->user()->hasRole(['supervisor', 'cosupervisor']))
                    <li class="nxl-item nxl-hasmenu {{ $isSupervisorMenu ? 'is-open' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link {{ $isSupervisorMenu ? 'is-active' : '' }}">
                            <span class="nxl-micon"><i class="feather-users"></i></span>
                            <span class="nxl-mtext">Supervision</span>
                            <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu" @if($isSupervisorMenu) style="display:block;" @endif>
                            <li class="nxl-item">
                                <a class="nxl-link {{ request()->routeIs('supervisor.students.index') ? 'is-active' : '' }}" href="{{ route('supervisor.students.index') }}">My Students</a>
                            </li>
                            <li class="nxl-item">
                                <a class="nxl-link {{ request()->routeIs('defense.schedule') ? 'is-active' : '' }}" href="{{ route('defense.schedule') }}">Defense Schedule</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(auth()->user() && auth()->user()->hasRole('examiner'))
                    <li class="nxl-item">
                        <a href="{{ route('examiner.defenses.index') }}" class="nxl-link {{ $isExaminerMenu ? 'is-active' : '' }}">
                            <span class="nxl-micon"><i class="feather-check-square"></i></span>
                            <span class="nxl-mtext">My Evaluations</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user() && auth()->user()->hasRole('librarian'))
                    <li class="nxl-item nxl-caption mt-2">
                        <label>Library</label>
                    </li>
                    <li class="nxl-item">
                        <a href="{{ route('profile.edit') }}" class="nxl-link {{ $isLibrarianMenu ? 'is-active' : '' }}">
                            <span class="nxl-micon"><i class="feather-user"></i></span>
                            <span class="nxl-mtext">Profile Settings</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user() && auth()->user()->hasRole(['admin', 'coordinator']))
                    <li class="nxl-item nxl-caption mt-2">
                        <label>Administration</label>
                    </li>

                    <li class="nxl-item nxl-hasmenu {{ $isAdminMenu ? 'is-open' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link {{ $isAdminMenu ? 'is-active' : '' }}">
                            <span class="nxl-micon"><i class="feather-settings"></i></span>
                            <span class="nxl-mtext">Management</span>
                            <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu" @if($isAdminMenu) style="display:block;" @endif>
                            <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('admin.users.index') ? 'is-active' : '' }}" href="{{ route('admin.users.index') }}">Manage Users</a></li>
                            <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('admin.users.create') ? 'is-active' : '' }}" href="{{ route('admin.users.create') }}">Add New User</a></li>
                            <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('admin.groups.*') ? 'is-active' : '' }}" href="{{ route('admin.groups.index') }}">Student Groups</a></li>
                            <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('admin.theses.*') ? 'is-active' : '' }}" href="{{ route('admin.theses.index') }}">Manage Theses</a></li>
                            <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('admin.defenses.*') ? 'is-active' : '' }}" href="{{ route('admin.defenses.index') }}">Defense Sessions</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
