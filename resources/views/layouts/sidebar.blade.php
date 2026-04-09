<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('dashboard') }}" class="b-brand">
                <!-- <img src="{{ asset('assets/images/logo-full.png') }}" alt="" class="logo logo-lg" /> -->
                <!-- <img src="{{ asset('assets/images/logo-abr.png') }}" alt="" class="logo logo-sm" /> -->
                <h4 class="logo logo-lg">Thesis App</h4>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Navigation</label>
                </li>
                <li class="nxl-item">
                    <a href="{{ route('dashboard') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-grid"></i></span>
                        <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>
                
                <!-- Student Section -->
                @if(auth()->user() && auth()->user()->hasRole('student'))
                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-file-text"></i></span>
                        <span class="nxl-mtext">My Thesis</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('proposals.index') }}">My Proposal</a></li> 
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('thesis.versions.index') }}">Thesis Versions</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('defense.schedule') }}">Defense Schedule</a></li>
                    </ul>
                </li>
                @endif

                <!-- Supervisor / Co-Supervisor Section -->
                @if(auth()->user() && auth()->user()->hasRole(['supervisor', 'cosupervisor']))
                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-users"></i></span>
                        <span class="nxl-mtext">Supervision</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('supervisor.students.index') }}">My Students</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('defense.schedule') }}">Defense Schedule</a></li>
                    </ul>
                </li>
                @endif

                @if(auth()->user() && auth()->user()->hasRole('examiner'))
                <li class="nxl-item">
                    <a href="{{ route('examiner.defenses.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-check-square"></i></span>
                        <span class="nxl-mtext">My Evaluations</span>
                    </a>
                </li>
                @endif

                <!-- Admin / Coordinator Section -->
                @if(auth()->user() && auth()->user()->hasRole(['admin', 'coordinator']))
                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                         <span class="nxl-micon"><i class="feather-settings"></i></span>
                        <span class="nxl-mtext">Administration</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                         <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.users.index') }}">Manage Users</a></li>
                         <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.users.create') }}">Add New User</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.groups.index') }}">Student Groups</a></li>
                         <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.theses.index') }}">Manage Theses</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.defenses.index') }}">Defense Sessions</a></li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
