<style>
    .sup-refresh {
        --sup-surface: #ffffff;
        --sup-border: #d6e2f2;
        --sup-muted: #64758d;
        --sup-ink: #0f172a;
        --sup-primary: #1d4ed8;
        --sup-primary-soft: #eaf1ff;
    }

    html.app-skin-dark .sup-refresh {
        --sup-surface: #151d28;
        --sup-border: rgba(196, 213, 238, 0.16);
        --sup-muted: #9fb2c9;
        --sup-ink: #e8eef8;
        --sup-primary: #9fb9ff;
        --sup-primary-soft: rgba(159, 185, 255, 0.18);
    }

    .sup-refresh .ta-page-head {
        position: relative;
        overflow: hidden;
        border: 1px solid #d8e8ff;
        border-radius: 22px;
        background: linear-gradient(130deg, #f5f9ff 0%, #edf4ff 58%, #f2f8ff 100%);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
        padding: 20px;
        margin-bottom: 16px;
    }

    html.app-skin-dark .sup-refresh .ta-page-head {
        border-color: #35507a;
        background: linear-gradient(130deg, #1b2739 0%, #162131 58%, #182737 100%);
        box-shadow: 0 16px 34px rgba(0, 0, 0, 0.34);
    }

    .sup-refresh .ta-page-head::before {
        content: "";
        position: absolute;
        width: 250px;
        height: 250px;
        top: -120px;
        right: -90px;
        border-radius: 999px;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.16) 0%, transparent 74%);
        pointer-events: none;
    }

    .sup-refresh .ta-page-head > div {
        position: relative;
        z-index: 1;
    }

    .sup-refresh .ta-page-kicker {
        color: var(--sup-primary);
        letter-spacing: 0.08em;
        font-weight: 800;
    }

    .sup-refresh .ta-page-title {
        color: var(--sup-ink);
        letter-spacing: -0.02em;
    }

    .sup-refresh .ta-page-subtitle,
    .sup-refresh .text-muted {
        color: var(--sup-muted) !important;
    }

    .sup-refresh .ta-chip-link {
        border: 1px solid var(--sup-border);
        background: var(--sup-surface);
        color: #21466f;
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.06);
    }

    html.app-skin-dark .sup-refresh .ta-chip-link {
        color: #d4e1f4;
    }

    .sup-refresh .ta-chip-link:hover {
        color: var(--sup-primary);
        border-color: #bdd4fb;
    }

    .sup-refresh .ta-panel,
    .sup-refresh .sv-panel,
    .sup-refresh .svs-card,
    .sup-refresh .ss-stat {
        border: 1px solid var(--sup-border);
        border-radius: 18px;
        background: var(--sup-surface);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .sup-refresh .ta-panel-head,
    .sup-refresh .sv-panel-header,
    .sup-refresh .svs-card-head {
        border-bottom: 1px solid var(--sup-border);
        padding: 14px 16px;
        background: linear-gradient(180deg, #fbfdff 0%, #f7faff 100%);
    }

    html.app-skin-dark .sup-refresh .ta-panel-head,
    html.app-skin-dark .sup-refresh .sv-panel-header,
    html.app-skin-dark .sup-refresh .svs-card-head {
        background: linear-gradient(180deg, #1c2736 0%, #192332 100%);
    }

    .sup-refresh .ta-panel-head h3,
    .sup-refresh .ta-panel-head h4,
    .sup-refresh .sv-panel-header h2,
    .sup-refresh .sv-panel-header h3,
    .sup-refresh .svs-card-title,
    .sup-refresh .sv-title,
    .sup-refresh .sv-kpi-value,
    .sup-refresh .sv-final-title,
    .sup-refresh .sv-version-title,
    .sup-refresh .sv-timeline-label,
    .sup-refresh .sv-thesis-title,
    .sup-refresh .svs-title,
    .sup-refresh .svs-stat-value {
        color: var(--sup-ink) !important;
    }

    .sup-refresh .sv-kicker,
    .sup-refresh .svs-kicker,
    .sup-refresh .sv-kpi-note,
    .sup-refresh .sv-version-meta,
    .sup-refresh .sv-final-meta,
    .sup-refresh .sv-section-note,
    .sup-refresh .sv-panel-note,
    .sup-refresh .sv-timeline-date,
    .sup-refresh .sv-subtitle,
    .sup-refresh .svs-card-sub,
    .sup-refresh .svs-subtitle,
    .sup-refresh .svs-stat-label {
        color: var(--sup-muted) !important;
    }

    .sup-refresh .sv-kpi-icon,
    .sup-refresh .svs-stat-icon,
    .sup-refresh .ss-stat .icon,
    .sup-refresh .sv-chip,
    .sup-refresh .svs-action {
        color: var(--sup-primary);
        background: var(--sup-primary-soft);
        border-color: #c8daf8;
    }

    html.app-skin-dark .sup-refresh .sv-chip,
    html.app-skin-dark .sup-refresh .svs-action,
    html.app-skin-dark .sup-refresh .ss-chip {
        color: #d7e5fb;
        border-color: #35507a;
    }

    .sup-refresh .ss-title,
    .sup-refresh .ss-stat .value,
    .sup-refresh .ss-empty h4 {
        color: var(--sup-ink);
    }

    .sup-refresh .ss-sub,
    .sup-refresh .ss-stat .label,
    .sup-refresh .ss-empty,
    .sup-refresh .ss-empty p {
        color: var(--sup-muted);
    }

    .sup-refresh .ss-chip {
        border: 1px solid var(--sup-border);
        background: #f7faff;
        color: #3e5878;
    }

    html.app-skin-dark .sup-refresh .ss-chip {
        background: #1a2737;
    }

    .sup-refresh .ss-status.scheduled {
        color: #9a6400;
        background: #fff4dd;
        border-color: #f6d49a;
    }

    .sup-refresh .ss-status.completed {
        color: #0f7b46;
        background: #edfdf3;
        border-color: #bfead1;
    }

    .sup-refresh .ss-status.cancelled {
        color: #b42318;
        background: #fff2f0;
        border-color: #f7d0cb;
    }

    html.app-skin-dark .sup-refresh .ss-status.scheduled {
        color: #ffd99b;
        background: rgba(120, 85, 20, 0.34);
        border-color: rgba(173, 132, 63, 0.45);
    }

    html.app-skin-dark .sup-refresh .ss-status.completed {
        color: #a6f0c8;
        background: rgba(35, 115, 73, 0.32);
        border-color: rgba(90, 175, 133, 0.42);
    }

    html.app-skin-dark .sup-refresh .ss-status.cancelled {
        color: #ffb4ac;
        background: rgba(170, 40, 40, 0.34);
        border-color: rgba(223, 121, 114, 0.45);
    }

    @media (max-width: 767.98px) {
        .sup-refresh .ta-page-head {
            border-radius: 16px;
            padding: 16px;
        }

        .sup-refresh .ta-page-actions {
            width: 100%;
        }

        .sup-refresh .ta-chip-link {
            justify-content: center;
        }
    }
</style>
