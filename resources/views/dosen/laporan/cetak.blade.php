<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Absensi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #111; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h3>LAPORAN REKAPITULASI KEHADIRAN PERKULIAHAN</h3>
        <p>Sistem Absensi Kampus • Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Mata Kuliah</th>
                <th>Pertemuan</th>
                <th>Materi</th>
                <th>Total Hadir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sesis as $i => $sesi)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $sesi->tanggal }}</td>
                <td>{{ $sesi->jadwal->mataKuliah->nama_mk ?? '-' }}</td>
                <td>Ke-{{ $sesi->pertemuan_ke }}</td>
                <td>{{ $sesi->materi ?? '-' }}</td>
                <td>{{ $sesi->absensis->where('status', 'hadir')->count() }} Mahasiswa</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>