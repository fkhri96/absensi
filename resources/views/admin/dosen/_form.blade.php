<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold small">NIDN <span class="text-danger">*</span></label>
        <input type="text" name="nidn" value="{{ old('nidn', $dosen->nidn ?? '') }}" class="form-control @error('nidn') is-invalid @enderror" required>
        @error('nidn')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold small">NIP (Opsional)</label>
        <input type="text" name="nip" value="{{ old('nip', $dosen->nip ?? '') }}" class="form-control @error('nip') is-invalid @enderror">
        @error('nip')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold small">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $dosen->user->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold small">Email Login <span class="text-danger">*</span></label>
        <input type="email" name="email" value="{{ old('email', $dosen->user->email ?? '') }}" class="form-control @error('email') is-invalid @enderror" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold small">Password {{ isset($dosen) ? '(Kosongkan jika tidak diubah)' : '*' }}</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ isset($dosen) ? '' : 'required' }}>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold small">Fakultas</label>
        <select name="fakultas_id" class="form-select @error('fakultas_id') is-invalid @enderror">
            <option value="">-- Pilih Fakultas (Opsional) --</option>
            @foreach($fakultasList as $fak)
                <option value="{{ $fak->id }}" {{ old('fakultas_id', $dosen->fakultas_id ?? '') == $fak->id ? 'selected' : '' }}>
                    {{ $fak->nama_fakultas }}
                </option>
            @endforeach
        </select>
        @error('fakultas_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold small">Jabatan Fungsional</label>
        <input type="text" name="jabatan_fungsional" value="{{ old('jabatan_fungsional', $dosen->jabatan_fungsional ?? '') }}" class="form-control @error('jabatan_fungsional') is-invalid @enderror" placeholder="Contoh: Lektor / Asisten Ahli">
        @error('jabatan_fungsional')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold small">No. HP</label>
        <input type="text" name="no_hp" value="{{ old('no_hp', $dosen->no_hp ?? '') }}" class="form-control @error('no_hp') is-invalid @enderror">
        @error('no_hp')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>