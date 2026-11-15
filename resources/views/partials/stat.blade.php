@php
    $statLabel = $label ?? $title ?? $judul ?? $nama ?? $name ?? 'Statistik';
    $statValue = $value ?? $nilai ?? $angka ?? $count ?? $total ?? $jumlah ?? '0';
    $statNote  = $note ?? $ket ?? $keterangan ?? $subtitle ?? $sub ?? $delta ?? '';
    $statIcon  = $icon ?? $ikon ?? 'bi-bar-chart-fill';
@endphp

<div class="stat-card p-3 p-md-4 h-100">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="text-secondary small fw-semibold">{{ $statLabel }}</span>
        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
            <i class="bi {{ $statIcon }}"></i>
        </div>
    </div>
    <h3 class="fw-bold mb-1">{{ $statValue }}</h3>
    @if($statNote !== '')
        <small class="text-muted">{{ $statNote }}</small>
    @endif
</div>