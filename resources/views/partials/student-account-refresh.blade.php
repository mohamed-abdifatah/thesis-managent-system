<style>
    .stu-refresh {
        --stu-surface: #ffffff;
        --stu-border: #d9e4f2;
        --stu-muted: #64758d;
        --stu-ink: #0f172a;
        --stu-primary: #0f766e;
        --stu-primary-soft: #def7f2;
    }

    html.app-skin-dark .stu-refresh {
        --stu-surface: #151d28;
        --stu-border: rgba(196, 213, 238, 0.16);
        --stu-muted: #9fb2c9;
        --stu-ink: #e8eef8;
        --stu-primary: #74e0d2;
        --stu-primary-soft: rgba(116, 224, 210, 0.16);
    }

    .stu-refresh .ta-page-head {
        position: relative;
        overflow: hidden;
        border: 1px solid #cdebe6;
        border-radius: 22px;
        background: linear-gradient(125deg, #effbf9 0%, #e7f9f5 56%, #f5fcfb 100%);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
        padding: 20px;
        margin-bottom: 16px;
    }

    html.app-skin-dark .stu-refresh .ta-page-head {
        border-color: #35507a;
        background: linear-gradient(125deg, #193036 0%, #152a31 56%, #172c34 100%);
        box-shadow: 0 16px 34px rgba(0, 0, 0, 0.34);
    }

    .stu-refresh .ta-page-head::before {
        content: "";
        position: absolute;
        width: 250px;
        height: 250px;
        top: -120px;
        right: -90px;
        border-radius: 999px;
        background: radial-gradient(circle, rgba(15, 118, 110, 0.16) 0%, transparent 74%);
        pointer-events: none;
    }

    .stu-refresh .ta-page-head > div {
        position: relative;
        z-index: 1;
    }

    .stu-refresh .ta-page-kicker {
        color: #0f766e;
        letter-spacing: 0.08em;
        font-weight: 800;
    }

    html.app-skin-dark .stu-refresh .ta-page-kicker {
        color: #95e9df;
    }

    .stu-refresh .ta-page-title {
        color: var(--stu-ink);
        letter-spacing: -0.02em;
    }

    .stu-refresh .ta-page-subtitle {
        color: var(--stu-muted);
    }

    .stu-refresh .ta-chip-link {
        border: 1px solid var(--stu-border);
        background: var(--stu-surface);
        color: #1f4f52;
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.06);
    }

    html.app-skin-dark .stu-refresh .ta-chip-link {
        color: #d4e1f4;
    }

    .stu-refresh .ta-chip-link:hover {
        color: #0f766e;
        border-color: #b9e5df;
    }

    .stu-refresh .ta-chip-link.ta-primary {
        border-color: #0f766e;
        background: linear-gradient(90deg, #0f766e 0%, #0ea5a6 100%);
        color: #ffffff;
    }

    .stu-refresh .ta-panel {
        border: 1px solid var(--stu-border);
        border-radius: 18px;
        background: var(--stu-surface);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .stu-refresh .ta-panel-head {
        border-bottom: 1px solid var(--stu-border);
        padding: 14px 16px;
        background: linear-gradient(180deg, #fbfefd 0%, #f6fbfa 100%);
    }

    html.app-skin-dark .stu-refresh .ta-panel-head {
        background: linear-gradient(180deg, #1c2736 0%, #192332 100%);
    }

    .stu-refresh .ta-panel-head h3,
    .stu-refresh .tv-row-title,
    .stu-refresh .pi-title,
    .stu-refresh .ss-title,
    .stu-refresh .pv-hero-title,
    .stu-refresh .pc-section-title {
        color: var(--stu-ink);
    }

    .stu-refresh .ta-panel-body,
    .stu-refresh .text-muted,
    .stu-refresh .tv-sub,
    .stu-refresh .pi-sub,
    .stu-refresh .ss-sub,
    .stu-refresh .pv-block p,
    .stu-refresh .pc-check,
    .stu-refresh .pc-note,
    .stu-refresh .tv-note,
    .stu-refresh .tv-kpi,
    .stu-refresh .sd-kpi,
    .stu-refresh .sd-step p,
    .stu-refresh .pv-step p,
    .stu-refresh .tv-empty p,
    .stu-refresh .pi-empty p,
    .stu-refresh .ss-empty p {
        color: var(--stu-muted) !important;
    }

    .stu-refresh .sd-stat,
    .stu-refresh .pi-stat,
    .stu-refresh .tv-stat,
    .stu-refresh .ss-stat {
        border: 1px solid var(--stu-border);
        background: var(--stu-surface);
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
    }

    .stu-refresh .sd-stat .icon,
    .stu-refresh .pi-stat .icon,
    .stu-refresh .tv-stat .icon,
    .stu-refresh .ss-stat .icon,
    .stu-refresh .pi-avatar {
        color: var(--stu-primary);
        background: var(--stu-primary-soft);
        border-color: #c7ece6;
    }

    .stu-refresh .tv-chip,
    .stu-refresh .ss-chip,
    .stu-refresh .pv-chip,
    .stu-refresh .sd-status-pill,
    .stu-refresh .tv-kpi,
    .stu-refresh .sd-kpi,
    .stu-refresh .pv-block,
    .stu-refresh .pv-step,
    .stu-refresh .pc-note,
    .stu-refresh .pc-check,
    .stu-refresh .tv-note,
    .stu-refresh .pc-upload {
        border-color: var(--stu-border);
        background: #f8fcfb;
    }

    html.app-skin-dark .stu-refresh .tv-chip,
    html.app-skin-dark .stu-refresh .ss-chip,
    html.app-skin-dark .stu-refresh .pv-chip,
    html.app-skin-dark .stu-refresh .sd-status-pill,
    html.app-skin-dark .stu-refresh .tv-kpi,
    html.app-skin-dark .stu-refresh .sd-kpi,
    html.app-skin-dark .stu-refresh .pv-block,
    html.app-skin-dark .stu-refresh .pv-step,
    html.app-skin-dark .stu-refresh .pc-note,
    html.app-skin-dark .stu-refresh .pc-check,
    html.app-skin-dark .stu-refresh .tv-note,
    html.app-skin-dark .stu-refresh .pc-upload {
        background: #1a2737;
    }

    .stu-refresh .pi-action,
    .stu-refresh .tv-action-btn {
        border-color: #bde6df;
        color: #0f766e;
        background: #eaf9f6;
    }

    .stu-refresh .pi-action:hover,
    .stu-refresh .tv-action-btn:hover {
        border-color: #9ddad0;
        background: #dff5f0;
        color: #0f766e;
    }

    .stu-refresh .pi-status.approved,
    .stu-refresh .tv-status.approved,
    .stu-refresh .ss-status.completed,
    .stu-refresh .pv-status.success {
        color: #0f7b46;
        background: #edfdf3;
        border-color: #bfead1;
    }

    .stu-refresh .pi-status.pending,
    .stu-refresh .ss-status.scheduled,
    .stu-refresh .pv-status.pending {
        color: #9a6400;
        background: #fff4dd;
        border-color: #f6d49a;
    }

    .stu-refresh .pi-status.revision_required,
    .stu-refresh .tv-status.needs_changes,
    .stu-refresh .pv-status.warning {
        color: #9a6400;
        background: #fff4dd;
        border-color: #f6d49a;
    }

    .stu-refresh .pi-status.rejected,
    .stu-refresh .ss-status.cancelled,
    .stu-refresh .pv-status.danger {
        color: #b42318;
        background: #fff2f0;
        border-color: #f7d0cb;
    }

    html.app-skin-dark .stu-refresh .ta-table-shell table tbody tr:hover td,
    html.app-skin-dark .stu-refresh .tv-table tbody tr:hover td,
    html.app-skin-dark .stu-refresh .pi-table tbody tr:hover td,
    html.app-skin-dark .stu-refresh .ss-table tbody tr:hover td {
        background: #1a2737;
    }

    @media (max-width: 767.98px) {
        .stu-refresh .ta-page-head {
            border-radius: 16px;
            padding: 16px;
        }

        .stu-refresh .ta-page-actions {
            width: 100%;
        }

        .stu-refresh .ta-chip-link {
            justify-content: center;
        }
    }
</style>
