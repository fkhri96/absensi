<style>
/* ===== Perbaikan Dark Mode (Notifikasi, Mata Kuliah, Jadwal) ===== */
:is([data-bs-theme="dark"], [data-theme="dark"], .dark, .dark-mode, .theme-dark) {

    /* Kartu */
    & .notif-item,
    & .jadwal-card,
    & .mk-card,
    & .panel,
    & .filter-pill:not(.active) {
        background: #0f1b33 !important;
        border-color: rgba(148, 163, 184, 0.22) !important;
        color: #e5edff;
    }
    & .notif-item:hover,
    & .jadwal-card:hover,
    & .mk-card:hover {
        border-color: rgba(148, 163, 184, 0.45) !important;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.35);
    }
    & .filter-pill:not(.active) { color: #b6c4df; }
    & .filter-pill:not(.active):hover { background: #16264a !important; color: #fff; }

    /* Judul & teks */
    & h3, & h4, & h5, & h6,
    & .text-dark,
    & strong { color: #f1f5f9 !important; }
    & .text-muted,
    & .text-secondary { color: #9fb0cc !important; }
    & .text-primary { color: #7da2ff !important; }

    /* Latar utilitas Bootstrap */
    & .bg-light { background: rgba(255, 255, 255, 0.06) !important; }
    & .bg-white { background: #0f1b33 !important; }
    & .border,
    & .border-top { border-color: rgba(148, 163, 184, 0.22) !important; }

    /* Badge */
    & .badge.bg-light,
    & .filter-pill .badge {
        background: rgba(255, 255, 255, 0.12) !important;
        color: #fff !important;
    }
    & .mk-badge-code {
        background: rgba(125, 162, 255, 0.18);
        color: #9db8ff;
    }

    /* Ikon notifikasi */
    & .notif-icon { background-color: rgba(255, 255, 255, 0.08) !important; }
    & .notif-icon.text-warning   { color: #fbbf24 !important; }
    & .notif-icon.text-success   { color: #4ade80 !important; }
    & .notif-icon.text-primary   { color: #7da2ff !important; }
    & .notif-icon.text-info      { color: #38bdf8 !important; }
    & .notif-icon.text-secondary { color: #cbd5e1 !important; }

    /* Tombol & input */
    & .btn-ghost {
        background: rgba(255, 255, 255, 0.08) !important;
        color: #e5edff !important;
        border: 1px solid rgba(148, 163, 184, 0.3) !important;
    }
    & .btn-ghost:hover { background: rgba(255, 255, 255, 0.16) !important; }
    & .form-control,
    & .input-group-text {
        background: #0f1b33 !important;
        color: #e5edff !important;
        border-color: rgba(148, 163, 184, 0.3) !important;
    }
    & .form-control::placeholder { color: #7f91b0; }
}
</style>