<x-app-layout>
    @php
        $totalSessions = $sessions->count();
        $upcomingSessions = $sessions
            ->filter(fn ($session) => $session->status === 'scheduled' && $session->scheduled_at && $session->scheduled_at->isFuture())
            ->count();
        $completedSessions = $sessions->where('status', 'completed')->count();
        $cancelledSessions = $sessions->where('status', 'cancelled')->count();
    @endphp

    <style>
        .ss-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .ss-stat {
            border: 1px solid var(--ta-border);
            border-radius: 16px;
            background: linear-gradient(165deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ss-stat .icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #1d4ed8;
            background: #eaf1ff;
            border: 1px solid #d2e1ff;
        }

        .ss-stat .label {
            margin: 0;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #657892;
            font-weight: 700;
        }

        .ss-stat .value {
            margin: 2px 0 0;
            font-size: 1.32rem;
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #0f172a;
        }

        .ss-table tbody td {
            vertical-align: middle;
        }

        .ss-title {
            margin: 0;
            color: #10233e;
            font-weight: 700;
            line-height: 1.3;
            max-width: 280px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ss-sub {
            margin-top: 3px;
            color: #66758d;
            font-size: 0.76rem;
            line-height: 1.3;
        }

        .ss-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 5px 9px;
            border: 1px solid transparent;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 800;
        }

        .ss-status.scheduled {
            color: #855800;
            background: #fff8e9;
            border-color: #f7dba6;
        }

        .ss-status.completed {
            color: #0f7b46;
            background: #edfdf3;
            border-color: #bfead1;
        }

        .ss-status.cancelled {
            color: #b42318;
            background: #fff2f0;
            border-color: #f7d0cb;
        }

        .ss-committee {
            display: flex;
            flex-direction: column;
            gap: 5px;
            min-width: 220px;
        }

        .ss-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            border: 1px solid #d8e6f8;
            background: #f7faff;
            padding: 4px 10px;
            font-size: 0.73rem;
            font-weight: 700;
            color: #415a76;
            width: fit-content;
        }

        .ss-empty {
            text-align: center;
            padding: 40px 16px;
            color: #66758d;
        }

        .ss-empty i {
            font-size: 1.7rem;
            color: #90a4bf;
            display: inline-block;
            margin-bottom: 10px;
        }

        .ss-empty h4 {
            margin: 0;
            color: #10233e;
            font-size: 1.04rem;
        }

        .ss-empty p {
            margin: 6px 0 0;
            font-size: 0.84rem;
        }

        html.app-skin-dark .ss-stat {
            background: linear-gradient(165deg, #151e2b 0%, #1b2636 100%);
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.25);
        }

        html.app-skin-dark .ss-stat .icon {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .ss-stat .label,
        html.app-skin-dark .ss-sub,
        html.app-skin-dark .ss-empty,
        html.app-skin-dark .ss-empty p {
            color: #a3b1c4;
        }

        html.app-skin-dark .ss-stat .value,
        html.app-skin-dark .ss-title,
        html.app-skin-dark .ss-empty h4 {
            color: #e6edf7;
        }

        html.app-skin-dark .ss-chip {
            color: #c8d3e3;
            background: #1c2736;
            border-color: rgba(255, 255, 255, 0.14);
        }

        html.app-skin-dark .ss-status.scheduled {
            color: #ffd99b;
            background: rgba(120, 85, 20, 0.34);
            border-color: rgba(173, 132, 63, 0.45);
        }

        html.app-skin-dark .ss-status.completed {
            color: #a6f0c8;
            background: rgba(35, 115, 73, 0.32);
            border-color: rgba(90, 175, 133, 0.42);
        }

        html.app-skin-dark .ss-status.cancelled {
            color: #ffb4ac;
            background: rgba(170, 40, 40, 0.34);
            border-color: rgba(223, 121, 114, 0.45);
        }

        @media (max-width: 1199px) {
            .ss-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {
            .ss-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @include('partials.student-account-refresh')

    <div class="{{ auth()->user()->hasRole('student') ? 'stu-refresh' : '' }}">
    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Academic Workflow</span>
            <h1 class="ta-page-title">Defense Schedule</h1>
            <p class="ta-page-subtitle">Track upcoming sessions, committee assignments, and final defense outcomes.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('dashboard') }}" class="ta-chip-link">
                <i class="feather-grid"></i>
                Dashboard
            </a>
            @if(auth()->user()->hasRole('student'))
                <a href="{{ route('thesis.versions.index') }}" class="ta-chip-link">
                    <i class="feather-upload-cloud"></i>
                    Thesis Versions
                </a>
            @endif
        </div>
    </div>

    <section class="ss-stats" aria-label="Defense schedule summary cards">
        <article class="ss-stat">
            <span class="icon"><i class="feather-calendar"></i></span>
            <div>
                <p class="label">Total Sessions</p>
                <p class="value">{{ number_format($totalSessions) }}</p>
            </div>
        </article>
        <article class="ss-stat">
            <span class="icon"><i class="feather-clock"></i></span>
            <div>
                <p class="label">Upcoming</p>
                <p class="value">{{ number_format($upcomingSessions) }}</p>
            </div>
        </article>
        <article class="ss-stat">
            <span class="icon"><i class="feather-check-circle"></i></span>
            <div>
                <p class="label">Completed</p>
                <p class="value">{{ number_format($completedSessions) }}</p>
            </div>
        </article>
        <article class="ss-stat">
            <span class="icon"><i class="feather-x-circle"></i></span>
            <div>
                <p class="label">Cancelled</p>
                <p class="value">{{ number_format($cancelledSessions) }}</p>
            </div>
        </article>
    </section>

    <div class="ta-panel">
        <div class="ta-panel-head">
            <div>
                <h3>Defense Sessions</h3>
                <span class="text-muted small">{{ $sessions->count() }} total records</span>
            </div>
        </div>
        <div class="ta-table-shell">
            <table class="table table-hover mb-0 ss-table">
                <thead>
                    <tr>
                        <th>Thesis</th>
                        <th>Schedule</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Committee</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                        <tr>
                            <td>
                                <p class="ss-title" title="{{ $session->thesis?->title ?? 'N/A' }}">{{ $session->thesis?->title ?? 'N/A' }}</p>
                                <span class="ss-sub">{{ $session->thesis?->student?->user?->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="ss-chip">
                                    <i class="feather-calendar"></i>
                                    {{ $session->scheduled_at?->format('M d, Y') ?? '-' }}
                                </span>
                                <span class="ss-sub d-block">{{ $session->scheduled_at?->format('h:i A') ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="ss-status {{ $session->status }}">{{ $session->status }}</span>
                            </td>
                            <td>
                                <span class="ss-chip"><i class="feather-map-pin"></i> {{ $session->location ?? 'TBD' }}</span>
                            </td>
                            <td>
                                <div class="ss-committee">
                                    @forelse($session->committeeMembers as $member)
                                        <span class="ss-sub">{{ $member->user?->name ?? 'Examiner' }} ({{ ucfirst($member->role) }})</span>
                                    @empty
                                        <span class="ss-sub">No committee assigned</span>
                                    @endforelse
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="ss-empty">
                                    <i class="feather-calendar"></i>
                                    <h4>No defense sessions scheduled yet</h4>
                                    <p>Your defense information will appear here once scheduled.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
</x-app-layout>
