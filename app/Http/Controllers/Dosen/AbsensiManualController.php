<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\SesiAbsensi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiManualController extends Controller
{
    private const STATUS = ['hadir', 'izin', 'sakit', 'alpha'];

    /** ID dosen (tabel dosens) dari akun yang sedang login. */
    private function dosenId(): int
    {
        return (int) (Dosen::where('user_id', auth()->id())->value('id')
            ?? abort(403, 'Akun ini tidak terdaftar sebagai dosen.'));
    }

    /**
     * Jadwal milik dosen ini. Dicocokkan lewat jadwals.dosen_id,
     * atau lewat dosen pengampu mata kuliahnya jika jadwals.dosen_id kosong.
     */
    private function jadwalDosen(): Builder
    {
        $dosenId = $this->dosenId();

        return Jadwal::where(function (Builder $q) use ($dosenId) {
            $q->where('dosen_id', $dosenId)
              ->orWhereHas('mataKuliah', fn (Builder $m) => $m->where('dosen_id', $dosenId));
        });
    }

    private function pastikanJadwalMilikDosen(Jadwal $jadwal): void
    {
        $dosenId = $this->dosenId();
        $jadwal->loadMissing('mataKuliah');

        $milik = (int) $jadwal->dosen_id === $dosenId
            || (int) optional($jadwal->mataKuliah)->dosen_id === $dosenId;

        abort_unless($milik, 403);
    }

    private function pastikanSesiMilikDosen(SesiAbsensi $sesi): void
    {
        $sesi->loadMissing('jadwal');
        $this->pastikanJadwalMilikDosen($sesi->jadwal);
    }

    /** Mahasiswa di kelas sesi ini (berdasarkan prodi mata kuliah). */
    private function mahasiswaKelas(SesiAbsensi $sesi)
    {
        $sesi->loadMissing('jadwal.mataKuliah');

        return Mahasiswa::with('user')
            ->where('prodi_id', $sesi->jadwal->mataKuliah->prodi_id)
            ->orderBy('nim')
            ->get();
    }

    // READ: halaman utama
    public function index(Request $request)
    {
        $jadwalList = $this->jadwalDosen()
            ->with('mataKuliah')
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        $sesiList = SesiAbsensi::with('jadwal.mataKuliah')
            ->whereHas('jadwal', fn (Builder $q) => $q->where(function (Builder $w) {
                $dosenId = $this->dosenId();
                $w->where('dosen_id', $dosenId)
                  ->orWhereHas('mataKuliah', fn (Builder $m) => $m->where('dosen_id', $dosenId));
            }))
            ->latest('tanggal')
            ->latest('id')
            ->get();

        $sesi = null;
        $mahasiswa = collect();
        $statusMap = collect();

        if ($request->filled('sesi_id')) {
            $sesi = $sesiList->firstWhere('id', (int) $request->sesi_id);
            abort_unless($sesi, 404);

            $mahasiswa = $this->mahasiswaKelas($sesi);
            $statusMap = Absensi::where('sesi_absensi_id', $sesi->id)
                ->get()
                ->keyBy('mahasiswa_id');
        }

        return view('dosen.absensi-manual.index', compact(
            'jadwalList', 'sesiList', 'sesi', 'mahasiswa', 'statusMap'
        ));
    }

    // CREATE SESI: buat pertemuan tanpa QR
    public function buatSesi(Request $request)
    {
        $data = $request->validate([
            'jadwal_id'    => 'required|exists:jadwals,id',
            'pertemuan_ke' => 'nullable|integer|min:1|max:99',
            'tanggal'      => 'required|date',
            'materi'       => 'nullable|string|max:255',
        ]);

        $jadwal = Jadwal::with('mataKuliah')->findOrFail($data['jadwal_id']);
        $this->pastikanJadwalMilikDosen($jadwal);

        $pertemuan = $data['pertemuan_ke']
            ?? ((int) SesiAbsensi::where('jadwal_id', $jadwal->id)->max('pertemuan_ke') + 1);

        $sesi = SesiAbsensi::firstOrCreate(
            ['jadwal_id' => $jadwal->id, 'pertemuan_ke' => $pertemuan],
            [
                'materi'        => $data['materi'] ?? '-',
                'tanggal'       => $data['tanggal'],
                'status'        => 'tutup',
                'qr_token'      => null,
                'qr_expired_at' => null,
            ]
        );

        return redirect()
            ->route('dosen.absensi.manual', ['sesi_id' => $sesi->id])
            ->with('success', 'Pertemuan siap diisi.');
    }

    // UPDATE: simpan status banyak mahasiswa sekaligus
    public function simpan(Request $request, SesiAbsensi $sesi)
    {
        $this->pastikanSesiMilikDosen($sesi);

        $data = $request->validate([
            'status'       => 'nullable|array',
            'status.*'     => 'nullable|in:' . implode(',', self::STATUS),
            'keterangan'   => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:255',
        ]);

        $idValid = $this->mahasiswaKelas($sesi)->pluck('id')->all();

        DB::transaction(function () use ($data, $sesi, $idValid) {
            foreach ($data['status'] ?? [] as $mahasiswaId => $status) {
                if (! $status || ! in_array((int) $mahasiswaId, $idValid, true)) {
                    continue;
                }

                Absensi::updateOrCreate(
                    [
                        'sesi_absensi_id' => $sesi->id,
                        'mahasiswa_id'    => $mahasiswaId,
                    ],
                    [
                        'status'       => $status,
                        'metode'       => 'manual',
                        'keterangan'   => data_get($data, "keterangan.$mahasiswaId"),
                        'dicatat_oleh' => auth()->id(),
                    ]
                );
            }
        });

        return redirect()
            ->route('dosen.absensi.manual', ['sesi_id' => $sesi->id])
            ->with('success', 'Absensi manual berhasil disimpan.');
    }

    // DELETE: kembalikan satu mahasiswa ke "belum tercatat"
    public function destroy(SesiAbsensi $sesi, Absensi $absensi)
    {
        $this->pastikanSesiMilikDosen($sesi);
        abort_unless($absensi->sesi_absensi_id === $sesi->id, 404);

        $absensi->delete();

        return redirect()
            ->route('dosen.absensi.manual', ['sesi_id' => $sesi->id])
            ->with('success', 'Catatan absensi dihapus.');
    }
}