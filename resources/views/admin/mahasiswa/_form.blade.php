<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">NIM</label>
        <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name', $mahasiswa->user->name ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Email Login</label>
        <input type="email" name="email" value="{{ old('email', $mahasiswa->user->email ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Password {{ isset($mahasiswa) ? '(Kosongkan jika tidak diubah)' : '' }}</label>
        <input type="password" name="password" class="form-control" {{ isset($mahasiswa) ? '' : 'required' }}>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Program Studi</label>
        <select name="prodi_id" class="form-select">
            <option value="">-- Pilih Prodi (Opsional) --</option>
            @foreach($prodis as $prodi)
                <option value="{{ $prodi->id }}" {{ old('prodi_id', $mahasiswa->prodi_id ?? '') == $prodi->id ? 'selected' : '' }}>
                    {{ $prodi->nama_prodi }} ({{ $prodi->jenjang }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Angkatan</label>
        <input type="number" name="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan ?? date('Y')) }}" class="form-control" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-select" required>
            <option value="L" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">No. HP</label>
        <input type="text" name="no_hp" value="{{ old('no_hp', $mahasiswa->no_hp ?? '') }}" class="form-control">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Alamat</label>
        <input type="text" name="alamat" value="{{ old('alamat', $mahasiswa->alamat ?? '') }}" class="form-control">
    </div>
</div>