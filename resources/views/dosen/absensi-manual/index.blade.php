@extends('layouts.app')
@section('title', 'Kelola Absensi')
@section('page_title', 'Kelola Absensi Manual')

@php
    // nilai di database => label tampil
    $opsiStatus = [
        'hadir' => 'Hadir',
        'izin'  => 'Izin',
        'sakit' => 'Sakit',
        'alpha' => 'Alfa',
    ];
@endphp

@section('content')

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- ============ 1. BUAT PERTEMUAN ============ --}}
<div class="card card-body mb-4">
    <h5 class="fw-bold mb-3">Buat Pertemuan Baru</h5>

    @if ($jadwalList->isEmpty())
        <p class="text-muted mb-0">Belum ada jadwal mengajar untuk akun ini.</p>
    @else
    <form method="POST" action="{{ route('dosen.absensi.manual.buat') }}" class="row g-3">
        @csrf
        <div class="col-md-5">
            <label class="form-label">Kelas / Mata Kuliah</label>
            <select name="jadwal_id" class="form-select" required>
                @foreach ($jadwalList as $j)
                    <option value="{{ $j->id }}">
                        {{ $j->mataKuliah->nama_mk }} · {{ $j->hari }}
                        {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Pertemuan ke-</label>
            <input type="number" name="pertemuan_ke" class="form-control" min="1" max="99"
                   placeholder="Otomatis">
        </div>
        <div class="col-md-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control"
                   value="{{ now()->toDateString() }}" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Materi</label>
            <input type="text" name="materi" class="form-control" maxlength="255" placeholder="Opsional">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Buat &amp; Isi Absensi</button>
        </div>
    </form>
    @endif
</div>

{{-- ============ 2. PILIH PERTEMUAN YANG SUDAH ADA ============ --}}
<form method="GET" action="{{ route('dosen.absensi.manual') }}" class="card card-body mb-4">
    <label for="sesi_id" class="form-label fw-semibold">Pertemuan yang sudah dibuat</label>
    <select name="sesi_id" id="sesi_id" class="form-select" onchange="this.form.submit()">
        <option value="">-- Pilih pertemuan --</option>
        @foreach ($sesiList as $s)
            <option value="{{ $s->id }}" @selected(optional($sesi)->id === $s->id)>
                {{ $s->jadwal->mataKuliah->nama_mk }} ·
                Pertemuan ke-{{ $s->pertemuan_ke }} ·
                {{ \Carbon\Carbon::parse($s->tanggal)->format('d M Y') }}
            </option>
        @endforeach
    </select>
</form>

{{-- ============ 3. TABEL ABSENSI ============ --}}
@if ($sesi)

<form method="POST" action="{{ route('dosen.absensi.manual.simpan', $sesi) }}">
    @csrf
    @method('PUT')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div class="text-muted small">
            {{ $mahasiswa->count() }} mahasiswa · {{ $statusMap->count() }} sudah tercatat
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-success btn-sm" onclick="isiSemua('hadir')">
                Semua hadir
            </button>
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th style="width: 120px;">NIM</th>
                    <th>Nama</th>
                    <th style="width: 200px;">Status</th>
                    <th style="width: 220px;">Keterangan</th>
                    <th class="text-end" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mahasiswa as $mhs)
                    @php
                        $absen  = $statusMap->get($mhs->id);
                        $nilai  = optional($absen)->status ?? '';
                    @endphp
                    <tr>
                        <td>{{ $mhs->nim }}</td>
                        <td>{{ $mhs->user->name ?? '-' }}</td>
                        <td>
                            <div class="sx">
                                <input type="hidden" name="status[{{ $mhs->id }}]" value="{{ $nilai }}">

                                <button type="button" class="sx-btn"
                                        data-s="{{ $nilai ?: 'kosong' }}"
                                        aria-haspopup="listbox" aria-expanded="false"
                                        aria-label="Status {{ $mhs->user->name ?? $mhs->nim }}">
                                    <span class="sx-label">{{ $opsiStatus[$nilai] ?? '-- Pilih --' }}</span>
                                    <svg class="sx-chev" viewBox="0 0 16 16" aria-hidden="true">
                                        <path d="m3 6 5 5 5-5" fill="none" stroke="currentColor"
                                              stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>

                                <div class="sx-menu" role="listbox">
                                    <button type="button" class="sx-opt" data-s="kosong" data-v="">
                                        <span class="dot"></span>-- Pilih --
                                    </button>
                                    @foreach ($opsiStatus as $val => $label)
                                        <button type="button" class="sx-opt" data-s="{{ $val }}" data-v="{{ $val }}">
                                            <span class="dot"></span>{{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="text" name="keterangan[{{ $mhs->id }}]"
                                   class="form-control form-control-sm"
                                   value="{{ optional($absen)->keterangan }}"
                                   placeholder="Opsional" maxlength="255">
                        </td>
                        <td class="text-end">
                            @if ($absen)
                                <button type="submit" form="form-hapus-{{ $absen->id }}"
                                        class="btn btn-sm btn-outline-secondary"
                                        onclick="return confirm('Hapus catatan absensi ini?')">
                                    Hapus
                                </button>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Belum ada mahasiswa di kelas ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</form>

{{-- Form hapus, diletakkan di luar form utama --}}
@foreach ($mahasiswa as $mhs)
    @php $absen = $statusMap->get($mhs->id); @endphp
    @if ($absen)
        <form id="form-hapus-{{ $absen->id }}" method="POST"
              action="{{ route('dosen.absensi.manual.hapus', [$sesi, $absen]) }}" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endif
@endforeach

@endif

@endsection

@push('scripts')
<script>
(function () {
    const LABEL = { '': '-- Pilih --', hadir: 'Hadir', izin: 'Izin', sakit: 'Sakit', alpha: 'Alfa' };
    const semua = [];
    let aktif = null;

    function setNilai(sx, val) {
        sx.input.value = val;
        sx.btn.dataset.s = val || 'kosong';
        sx.label.textContent = LABEL[val] ?? '-- Pilih --';
        sx.menu.querySelectorAll('.sx-opt').forEach(function (o) {
            o.classList.toggle('active', o.dataset.v === val);
        });
    }

    function posisikan(sx) {
        const r = sx.btn.getBoundingClientRect();
        const tinggi = sx.menu.offsetHeight;
        const adaRuangBawah = window.innerHeight - r.bottom > tinggi + 12;
        sx.menu.style.left  = r.left + 'px';
        sx.menu.style.width = r.width + 'px';
        sx.menu.style.top   = (adaRuangBawah ? r.bottom + 6 : r.top - tinggi - 6) + 'px';
    }

    function tutup() {
        if (!aktif) return;
        aktif.menu.classList.remove('show');
        aktif.btn.setAttribute('aria-expanded', 'false');
        aktif.root.classList.remove('open');
        aktif = null;
    }

    function buka(sx) {
        tutup();
        sx.menu.classList.add('show');
        sx.btn.setAttribute('aria-expanded', 'true');
        sx.root.classList.add('open');
        aktif = sx;
        posisikan(sx);
    }

    document.querySelectorAll('.sx').forEach(function (root) {
        const sx = {
            root:  root,
            input: root.querySelector('input[type="hidden"]'),
            btn:   root.querySelector('.sx-btn'),
            label: root.querySelector('.sx-label'),
            menu:  root.querySelector('.sx-menu'),
        };
        semua.push(sx);
        setNilai(sx, sx.input.value);

        sx.btn.addEventListener('click', function (e) {
            e.stopPropagation();
            aktif === sx ? tutup() : buka(sx);
        });

        sx.menu.addEventListener('click', function (e) { e.stopPropagation(); });

        sx.menu.querySelectorAll('.sx-opt').forEach(function (o) {
            o.addEventListener('click', function (e) {
                e.stopPropagation();
                setNilai(sx, o.dataset.v);
                tutup();
            });
        });
    });

    document.addEventListener('click', tutup);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') tutup(); });
    window.addEventListener('scroll', tutup, true);
    window.addEventListener('resize', tutup);

    // Dipanggil tombol "Semua hadir"
    window.isiSemua = function (val) {
        semua.forEach(function (sx) { setNilai(sx, val); });
    };
})();
</script>
@endpush