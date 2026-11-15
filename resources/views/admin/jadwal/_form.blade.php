<div class="row g-3">
    <div class="col-md-6">
        <label for="mata_kuliah_id" class="form-label fw-semibold small">Mata Kuliah <span class="text-danger">*</span></label>
        <select id="mata_kuliah_id" name="mata_kuliah_id" class="form-select @error('mata_kuliah_id') is-invalid @enderror" required>
            <option value="">-- Pilih Mata Kuliah --</option>
            @foreach(($mataKuliahs ?? $matkuls ?? []) as $mk)
                <option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $jadwal->mata_kuliah_id ?? '') == $mk->id ? 'selected' : '' }}>
                    {{ $mk->kode_mk ? $mk->kode_mk . ' - ' : '' }}{{ $mk->nama_mk }}
                </option>
            @endforeach
        </select>
        @error('mata_kuliah_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="dosen_id" class="form-label fw-semibold small">Dosen Pengampu <span class="text-danger">*</span></label>
        <select id="dosen_id" name="dosen_id" class="form-select @error('dosen_id') is-invalid @enderror" required>
            <option value="">-- Pilih Dosen --</option>
            @foreach(($dosens ?? []) as $dsn)
                <option value="{{ $dsn->id }}" {{ old('dosen_id', $jadwal->dosen_id ?? '') == $dsn->id ? 'selected' : '' }}>
                    {{ $dsn->nidn }} - {{ $dsn->user->name ?? '-' }}
                </option>
            @endforeach
        </select>
        @error('dosen_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="hari" class="form-label fw-semibold small">Hari <span class="text-danger">*</span></label>
        <select id="hari" name="hari" class="form-select @error('hari') is-invalid @enderror" required>
            <option value="">-- Pilih Hari --</option>
            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                <option value="{{ $hari }}" {{ old('hari', $jadwal->hari ?? '') == $hari ? 'selected' : '' }}>
                    {{ $hari }}
                </option>
            @endforeach
        </select>
        @error('hari')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="jam_mulai" class="form-label fw-semibold small">Jam Mulai <span class="text-danger">*</span></label>
        <input type="time" id="jam_mulai" name="jam_mulai"
               value="{{ old('jam_mulai', isset($jadwal->jam_mulai) ? \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') : '') }}"
               class="form-control @error('jam_mulai') is-invalid @enderror" required>
        @error('jam_mulai')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="jam_selesai" class="form-label fw-semibold small">Jam Selesai <span class="text-danger">*</span></label>
        <input type="time" id="jam_selesai" name="jam_selesai"
               value="{{ old('jam_selesai', isset($jadwal->jam_selesai) ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '') }}"
               class="form-control @error('jam_selesai') is-invalid @enderror" required>
        @error('jam_selesai')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="ruang" class="form-label fw-semibold small">Ruang Kelas <span class="text-danger">*</span></label>
        <input type="text" id="ruang" name="ruang"
               value="{{ old('ruang', $jadwal->ruang ?? '') }}"
               class="form-control @error('ruang') is-invalid @enderror"
               placeholder="Contoh: Lab Komputer 1 / R.302" required>
        @error('ruang')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>