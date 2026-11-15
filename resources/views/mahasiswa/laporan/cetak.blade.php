<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Kehadiran - {{ $mahasiswa->nama ?? 'Mahasiswa' }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .header h2, .header h4 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px 12px; text-align: left; }
        th { background-color: #f4f6fb; }
        .text-center { text-align: center; }
        .badge { padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .bg-success { background: #d1e7dd; color: #0f5132; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>REKAPITULASI KEHADIRAN MAHASISWA</h2>
        <h4>Sistem Absensi Kampus</h4>
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Nama:</strong> {{ $mahasiswa->nama ?? auth()->user()->name }}<br>
        <strong>NIM:</strong> {{ $mahasiswa->nim ?? '-' }}<br>
        <strong>Program Studi:</strong> {{ $mahasiswa->prodi->nama_prodi ?? '-' }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Mata Kuliah</th>
                <th style="width: 15%;">Pertemuan</th>
                <th style="width: 25%;">Waktu Presensi</th>
                <th style="width: 15%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensis ?? [] as $index => $absen)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $absen->sesiAbsensi->jadwal->mataKuliah->nama_mk ?? '-' }}</td>
                    <td>Pertemuan ke-{{ $absen->sesiAbsensi->pertemuan_ke ?? '-' }}</td>
                    <td>{{ $absen->created_at ? $absen->created_at->format('d/m/Y H:i') : '-' }}</td>
                    <td class="text-center">
                        <span class="badge bg-success">{{ $absen->status }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px;">Belum ada riwayat kehadiran yang tercatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>