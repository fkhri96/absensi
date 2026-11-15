<div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-dark mx-auto" style="max-width: 340px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold">
            <i class="bi bi-qr-code-scan me-1"></i> Presensi QR Aktif
        </span>
        <small class="text-muted fw-semibold">{{ date('H:i') }} WIB</small>
    </div>

    <div class="text-center p-3 bg-light rounded-4 border mb-3">
        {{-- Ilustrasi QR Code SVG --}}
        <svg width="150" height="150" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="mx-auto d-block">
            <rect width="100" height="100" rx="8" fill="white"/>
            <!-- Pojok Kiri Atas -->
            <rect x="10" y="10" width="26" height="26" rx="4" stroke="#0f172a" stroke-width="6"/>
            <rect x="18" y="18" width="10" height="10" rx="2" fill="#0f172a"/>
            <!-- Pojok Kanan Atas -->
            <rect x="64" y="10" width="26" height="26" rx="4" stroke="#0f172a" stroke-width="6"/>
            <rect x="72" y="18" width="10" height="10" rx="2" fill="#0f172a"/>
            <!-- Pojok Kiri Bawah -->
            <rect x="10" y="64" width="26" height="26" rx="4" stroke="#0f172a" stroke-width="6"/>
            <rect x="18" y="72" width="10" height="10" rx="2" fill="#0f172a"/>
            <!-- Pola Tengah -->
            <rect x="44" y="12" width="6" height="6" fill="#0f172a"/>
            <rect x="44" y="24" width="12" height="6" fill="#0f172a"/>
            <rect x="12" y="44" width="12" height="6" fill="#0f172a"/>
            <rect x="30" y="44" width="6" height="12" fill="#0f172a"/>
            <rect x="44" y="44" width="12" height="12" rx="2" fill="#2563eb"/>
            <rect x="64" y="44" width="12" height="6" fill="#0f172a"/>
            <rect x="82" y="44" width="8" height="12" fill="#0f172a"/>
            <rect x="44" y="64" width="6" height="12" fill="#0f172a"/>
            <rect x="56" y="70" width="12" height="6" fill="#0f172a"/>
            <rect x="74" y="64" width="16" height="6" fill="#0f172a"/>
            <rect x="64" y="82" width="10" height="8" fill="#0f172a"/>
            <rect x="80" y="78" width="10" height="12" fill="#0f172a"/>
        </svg>
        <div class="mt-2 small fw-bold text-secondary">KAMPUS-{{ date('Ymd') }}</div>
    </div>

    <div class="d-flex justify-content-between align-items-center small">
        <span class="text-muted">Status Sistem</span>
        <span class="fw-bold text-success">Siap Digunakan</span>
    </div>
</div>