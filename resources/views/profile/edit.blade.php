<x-app-layout>
    @php
        $profileThemeClass = '';
        $profileThemePartial = null;
        $profileRoleLabel = \Illuminate\Support\Str::headline(auth()->user()->role?->name ?? 'member');
        $emailVerified = method_exists(auth()->user(), 'hasVerifiedEmail') ? auth()->user()->hasVerifiedEmail() : true;

        if (auth()->user()->hasRole('student')) {
            $profileThemeClass = 'stu-refresh';
            $profileThemePartial = 'partials.student-account-refresh';
        } elseif (auth()->user()->hasRole('supervisor')) {
            $profileThemeClass = 'sup-refresh';
            $profileThemePartial = 'partials.supervisor-account-refresh';
        } elseif (auth()->user()->hasRole('admin') || auth()->user()->hasRole('coordinator')) {
            $profileThemeClass = 'adm-refresh';
            $profileThemePartial = 'partials.admin-account-refresh';
        }
    @endphp

    @if($profileThemePartial)
        @include($profileThemePartial)
    @endif

    <style>
        .pf-head.ta-page-head {
            position: relative;
            overflow: hidden;
            border: 1px solid var(--ta-border);
            border-radius: 22px;
            background: linear-gradient(130deg, #f8fbff 0%, #eef4ff 58%, #f4f9ff 100%);
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
            padding: 20px;
            margin-bottom: 16px;
        }

        html.app-skin-dark .pf-head.ta-page-head {
            border-color: rgba(196, 213, 238, 0.18);
            background: linear-gradient(130deg, #1b2739 0%, #162131 58%, #182737 100%);
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.34);
        }

        .pf-head.ta-page-head::before,
        .pf-head.ta-page-head::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .pf-head.ta-page-head::before {
            width: 250px;
            height: 250px;
            top: -120px;
            right: -90px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 74%);
        }

        .pf-head.ta-page-head::after {
            width: 170px;
            height: 170px;
            left: -70px;
            bottom: -90px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.14) 0%, transparent 72%);
        }

        .pf-head.ta-page-head > div {
            position: relative;
            z-index: 1;
        }

        .pf-meta {
            margin-top: 12px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .pf-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            border: 1px solid #c9daf6;
            background: #f3f8ff;
            color: #28486f;
            padding: 6px 11px;
            font-size: 0.76rem;
            font-weight: 700;
            line-height: 1;
        }

        html.app-skin-dark .pf-pill {
            border-color: rgba(143, 178, 255, 0.36);
            background: rgba(143, 178, 255, 0.16);
            color: #d7e5fb;
        }

        .pf-pill.is-success {
            border-color: #bfead1;
            background: #edfdf3;
            color: #0f7b46;
        }

        .pf-pill.is-warning {
            border-color: #f6d49a;
            background: #fff4dd;
            color: #9a6400;
        }

        html.app-skin-dark .pf-pill.is-success {
            color: #a6f0c8;
            border-color: rgba(90, 175, 133, 0.45);
            background: rgba(35, 115, 73, 0.32);
        }

        html.app-skin-dark .pf-pill.is-warning {
            color: #ffd99b;
            border-color: rgba(173, 132, 63, 0.45);
            background: rgba(120, 85, 20, 0.34);
        }

        @media (max-width: 767.98px) {
            .pf-head.ta-page-head {
                border-radius: 16px;
                padding: 16px;
            }
        }
    </style>

    <div class="{{ $profileThemeClass }}">
    <div class="ta-page-head pf-head">
        <div>
            <span class="ta-page-kicker">Account</span>
            <h1 class="ta-page-title">Profile Settings</h1>
            <p class="ta-page-subtitle">Update your profile details, password, and account security preferences from one place.</p>

            <div class="pf-meta">
                <span class="pf-pill"><i class="feather-user"></i> {{ auth()->user()->name }}</span>
                <span class="pf-pill"><i class="feather-mail"></i> {{ auth()->user()->email }}</span>
                <span class="pf-pill"><i class="feather-shield"></i> {{ $profileRoleLabel }}</span>
                <span class="pf-pill {{ $emailVerified ? 'is-success' : 'is-warning' }}">
                    <i class="feather-{{ $emailVerified ? 'check-circle' : 'alert-circle' }}"></i>
                    {{ $emailVerified ? 'Email Verified' : 'Email Unverified' }}
                </span>
            </div>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('dashboard') }}" class="ta-chip-link">
                <i class="feather-home"></i>
                Back to Dashboard
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-6">
            <div class="ta-panel mb-4">
                <div class="ta-panel-head">
                    <h3>Profile Information</h3>
                </div>
                <div class="ta-panel-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Danger Zone</h3>
                </div>
                <div class="ta-panel-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Update Password</h3>
                </div>
                <div class="ta-panel-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
