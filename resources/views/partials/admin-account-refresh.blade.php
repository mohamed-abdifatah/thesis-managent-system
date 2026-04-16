<style>
    .adm-refresh {
        --adm-surface: #ffffff;
        --adm-border: #deccbd;
        --adm-muted: #6f6559;
        --adm-ink: #221914;
        --adm-primary: #a34c1f;
        --adm-primary-strong: #7f3714;
        --adm-primary-soft: #fceee4;
    }

    html.app-skin-dark .adm-refresh {
        --adm-surface: #151d28;
        --adm-border: rgba(214, 195, 175, 0.2);
        --adm-muted: #b5a99b;
        --adm-ink: #e8eef8;
        --adm-primary: #f2b183;
        --adm-primary-strong: #dc965f;
        --adm-primary-soft: rgba(242, 177, 131, 0.16);
    }

    .adm-refresh .ta-page-head,
    .adm-refresh .page-header {
        position: relative;
        overflow: hidden;
        border: 1px solid #ebdacb;
        border-radius: 22px;
        background: linear-gradient(125deg, #fef8f1 0%, #fdf0e6 56%, #fffaf4 100%);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
        padding: 20px;
        margin-bottom: 16px;
    }

    html.app-skin-dark .adm-refresh .ta-page-head,
    html.app-skin-dark .adm-refresh .page-header {
        border-color: #4b5f7d;
        background: linear-gradient(125deg, #2d2520 0%, #27201b 56%, #251e19 100%);
        box-shadow: 0 16px 34px rgba(0, 0, 0, 0.34);
    }

    .adm-refresh .ta-page-head::before,
    .adm-refresh .page-header::before {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        top: -130px;
        right: -90px;
        border-radius: 999px;
        background: radial-gradient(circle, rgba(163, 76, 31, 0.16) 0%, transparent 74%);
        pointer-events: none;
    }

    .adm-refresh .ta-page-head > div,
    .adm-refresh .page-header > div {
        position: relative;
        z-index: 1;
    }

    .adm-refresh .ta-page-kicker {
        color: var(--adm-primary);
        letter-spacing: 0.08em;
        font-weight: 800;
    }

    .adm-refresh .ta-page-title,
    .adm-refresh .page-header-title,
    .adm-refresh .ta-admin .ta-page-title,
    .adm-refresh h3,
    .adm-refresh h4,
    .adm-refresh h5,
    .adm-refresh .ta-user-name,
    .adm-refresh .tt-title,
    .adm-refresh .ds-title,
    .adm-refresh .tg-group,
    .adm-refresh .dc-section h4,
    .adm-refresh .uf-card-head h3,
    .adm-refresh .uf-section h4 {
        color: var(--adm-ink) !important;
    }

    .adm-refresh .ta-page-subtitle,
    .adm-refresh .breadcrumb,
    .adm-refresh .text-muted,
    .adm-refresh .ta-admin .ta-page-subtitle,
    .adm-refresh .ta-admin .ta-subtext,
    .adm-refresh .ta-admin .ta-meta,
    .adm-refresh .ta-user-email,
    .adm-refresh .tt-sub,
    .adm-refresh .ds-sub,
    .adm-refresh .tg-sub,
    .adm-refresh .dc-form-note,
    .adm-refresh .uf-card-head p,
    .adm-refresh .uf-section p,
    .adm-refresh .gc-helper {
        color: var(--adm-muted) !important;
    }

    .adm-refresh .ta-panel,
    .adm-refresh .card,
    .adm-refresh .uf-card,
    .adm-refresh .modal-content {
        border: 1px solid var(--adm-border) !important;
        border-radius: 18px;
        background: var(--adm-surface);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
    }

    .adm-refresh .ta-panel-head,
    .adm-refresh .card-header,
    .adm-refresh .uf-card-head,
    .adm-refresh .modal-header {
        border-bottom: 1px solid var(--adm-border) !important;
        background: linear-gradient(180deg, #fffdfa 0%, #fff8f2 100%);
    }

    html.app-skin-dark .adm-refresh .ta-panel-head,
    html.app-skin-dark .adm-refresh .card-header,
    html.app-skin-dark .adm-refresh .uf-card-head,
    html.app-skin-dark .adm-refresh .modal-header {
        background: linear-gradient(180deg, #1c2736 0%, #192332 100%);
    }

    .adm-refresh .ta-chip-link,
    .adm-refresh .btn,
    .adm-refresh .ta-action-btn,
    .adm-refresh .tt-action,
    .adm-refresh .ds-action,
    .adm-refresh .gc-action {
        border-radius: 10px;
    }

    .adm-refresh .ta-chip-link.ta-primary,
    .adm-refresh .btn-primary,
    .adm-refresh .ta-admin .ta-chip-link.ta-primary,
    .adm-refresh .ta-action-btn.ta-action-edit,
    .adm-refresh .tt-action,
    .adm-refresh .ds-action,
    .adm-refresh .gc-action {
        border-color: var(--adm-primary) !important;
        background: linear-gradient(130deg, var(--adm-primary) 0%, var(--adm-primary-strong) 100%) !important;
        color: #ffffff !important;
    }

    .adm-refresh .btn-outline-secondary,
    .adm-refresh .btn-light,
    .adm-refresh .ta-chip-link,
    .adm-refresh .ta-action-btn.ta-action-delete,
    .adm-refresh .ta-admin .ta-chip-link {
        border-color: var(--adm-border) !important;
        background: var(--adm-surface) !important;
        color: #6f3e20 !important;
    }

    html.app-skin-dark .adm-refresh .btn-outline-secondary,
    html.app-skin-dark .adm-refresh .btn-light,
    html.app-skin-dark .adm-refresh .ta-chip-link,
    html.app-skin-dark .adm-refresh .ta-action-btn.ta-action-delete,
    html.app-skin-dark .adm-refresh .ta-admin .ta-chip-link {
        color: #d4e1f4 !important;
    }

    .adm-refresh .ta-user-stat,
    .adm-refresh .tg-stat,
    .adm-refresh .gc-stat,
    .adm-refresh .tt-stat,
    .adm-refresh .ds-stat,
    .adm-refresh .dc-stat,
    .adm-refresh .ta-admin .ta-stat-card,
    .adm-refresh .ta-admin .ta-hero,
    .adm-refresh .ta-admin .ta-focus,
    .adm-refresh .ta-admin .ta-mini,
    .adm-refresh .ta-admin .ta-callout,
    .adm-refresh .ta-admin .ta-action-list .ta-action,
    .adm-refresh .uf-user-pill,
    .adm-refresh .uf-info-card,
    .adm-refresh .gc-table tr,
    .adm-refresh .tt-row td,
    .adm-refresh .ds-row td {
        border-color: var(--adm-border) !important;
        background: #fffbf8 !important;
    }

    html.app-skin-dark .adm-refresh .ta-user-stat,
    html.app-skin-dark .adm-refresh .tg-stat,
    html.app-skin-dark .adm-refresh .gc-stat,
    html.app-skin-dark .adm-refresh .tt-stat,
    html.app-skin-dark .adm-refresh .ds-stat,
    html.app-skin-dark .adm-refresh .dc-stat,
    html.app-skin-dark .adm-refresh .ta-admin .ta-stat-card,
    html.app-skin-dark .adm-refresh .ta-admin .ta-hero,
    html.app-skin-dark .adm-refresh .ta-admin .ta-focus,
    html.app-skin-dark .adm-refresh .ta-admin .ta-mini,
    html.app-skin-dark .adm-refresh .ta-admin .ta-callout,
    html.app-skin-dark .adm-refresh .ta-admin .ta-action-list .ta-action,
    html.app-skin-dark .adm-refresh .uf-user-pill,
    html.app-skin-dark .adm-refresh .uf-info-card,
    html.app-skin-dark .adm-refresh .gc-table tr,
    html.app-skin-dark .adm-refresh .tt-row td,
    html.app-skin-dark .adm-refresh .ds-row td {
        background: #1a2737 !important;
    }

    .adm-refresh .ta-user-stat .icon,
    .adm-refresh .tg-stat .icon,
    .adm-refresh .gc-stat .icon,
    .adm-refresh .tt-stat .icon,
    .adm-refresh .ds-stat .icon,
    .adm-refresh .dc-stat .icon,
    .adm-refresh .ta-user-avatar,
    .adm-refresh .ta-admin .ta-stat-icon,
    .adm-refresh .ta-admin .ta-mini-icon,
    .adm-refresh .tt-chip,
    .adm-refresh .ds-chip,
    .adm-refresh .gc-chip,
    .adm-refresh .dc-role-pill,
    .adm-refresh .uf-user-pill .avatar {
        color: var(--adm-primary) !important;
        background: var(--adm-primary-soft) !important;
        border-color: #e8ccb8 !important;
    }

    .adm-refresh .table,
    .adm-refresh .table thead th,
    .adm-refresh .table tbody td {
        border-color: var(--adm-border) !important;
    }

    .adm-refresh .table tbody tr:hover td,
    .adm-refresh .tt-row:hover td,
    .adm-refresh .ds-row:hover td {
        background: #fff8f2 !important;
    }

    html.app-skin-dark .adm-refresh .table tbody tr:hover td,
    html.app-skin-dark .adm-refresh .tt-row:hover td,
    html.app-skin-dark .adm-refresh .ds-row:hover td {
        background: #1f2d3d !important;
    }

    .adm-refresh .form-control,
    .adm-refresh .form-select,
    .adm-refresh .input-group-text {
        border-color: var(--adm-border);
        background: var(--adm-surface);
        color: var(--adm-ink);
    }

    .adm-refresh .form-control:focus,
    .adm-refresh .form-select:focus {
        border-color: var(--adm-primary);
        box-shadow: 0 0 0 0.2rem rgba(163, 76, 31, 0.14);
    }

    @media (max-width: 767.98px) {
        .adm-refresh .ta-page-head,
        .adm-refresh .page-header {
            border-radius: 16px;
            padding: 16px;
        }

        .adm-refresh .ta-page-actions {
            width: 100%;
        }

        .adm-refresh .ta-chip-link {
            justify-content: center;
        }
    }
</style>
