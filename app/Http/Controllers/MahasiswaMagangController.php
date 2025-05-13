<?php

namespace App\Http\Controllers;

use App\Models\DokumenPengajuan;
use App\Models\Keahlian;
use App\Models\KeahlianLowongan;
use App\Models\KeahlianMahasiswa;
use App\Models\LowonganMagang;
use App\Models\PengajuanMagang;
use App\Models\ProfilDosen;
use App\Models\ProfilMahasiswa;
use App\Services\SPKService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaMagangController extends Controller
{
    public function magang(Request $request)
    {
        if ($request->ajax()) {
            $lowonganMagang = SPKService::getRecommendations(Auth::user()->user_id);
            return DataTables::of($lowonganMagang)
                ->addColumn('lowongan_id', function ($row) {
                    return $row['lowongan']->lowongan_id;
                })
                ->addColumn('skor', function ($row) {
                    return number_format($row['score'], 4);
                })
                ->addColumn('judul', function ($row) {
                    return $row['lowongan']->judul_lowongan;
                })
                ->addColumn('tipe_kerja_lowongan', function ($row) {
                    return $row['lowongan']->tipe_kerja_lowongan;
                })
                ->addColumn('deskripsi', function ($row) {
                    return $row['lowongan']->deskripsi;
                })
                ->addColumn('batas_pendaftaran', function ($row) {
                    $diff = date_diff(
                        date_create($row['lowongan']->batas_pendaftaran),
                        date_create(date('Y-m-d'))
                    );
                    return $diff->format('%a');
                })
                ->addColumn('gaji', function ($row) {
                    return $row['lowongan']->gaji;
                })
                ->addColumn('keahlian_lowongan', function ($row) {
                    $keahlian = '';
                    foreach ($row['lowongan']->keahlianLowongan as $keahlianLowongan) {
                        $keahlian .= $keahlianLowongan->keahlian->nama_keahlian . ', ';
                    }
                    return rtrim($keahlian, ', ');
                })
                ->make(true);
        }
        return view('mahasiswa.magang.index', [
            'keahlian' => Keahlian::all(),
            'tipeKerja' => LowonganMagang::TIPE_KERJA,
            'mahasiswa' => ProfilMahasiswa::where('mahasiswa_id', Auth::user()->user_id)->with('preferensiMahasiswa')->first(),
        ]);
    }

    public function detail($lowongan_id)
    {
        $lowonganMagang = collect(SPKService::getRecommendations(Auth::user()->user_id))
            ->firstWhere('lowongan.lowongan_id', $lowongan_id);
        $lowongan = $lowonganMagang['lowongan'];
        $score = $lowonganMagang['score'];
        // dd($lowonganMagang);

        return view('mahasiswa.magang.detail', [
            'lowongan' => $lowongan,
            'tingkat_kemampuan' => KeahlianLowongan::TINGKAT_KEMAMPUAN,
            'score' => $score,
        ]);
    }

    public function ajukan($lowongan_id)
    {
        $lowongan = LowonganMagang::find($lowongan_id);
        return view('mahasiswa.magang.ajukan.index', [
            'lowongan' => $lowongan,
            'user' => ProfilMahasiswa::where('mahasiswa_id', Auth::user()->user_id)->with('preferensiMahasiswa')->first(),
            'tingkat_kemampuan' => KeahlianLowongan::TINGKAT_KEMAMPUAN,
            'keahlian_mahasiswa' => KeahlianMahasiswa::where('mahasiswa_id', Auth::user()->user_id)->with('keahlian')->get(),
            'dosen' => ProfilDosen::select('nama', 'dosen_id')->get(),
        ]);
    }

    public function ajukanPost(Request $request, $lowongan_id)
    {
        DB::beginTransaction();
        try {
            $dataLowongan = $request->only(['dosen_id', 'catatan_mahasiswa']);
            $dataLowongan['mahasiswa_id'] = Auth::user()->user_id;
            $dataLowongan['lowongan_id'] = $lowongan_id;
            $dataLowongan['status'] = 'menunggu';
            $dataLowongan['tanggal_pengajuan'] = now()->format('Y-m-d');
            $pengajuanMagang = PengajuanMagang::create($dataLowongan);

            $dokumenInput = $request->file('dokumen_input', []);
            $jenisDokumen = $request->input('jenis_dokumen', []);
            foreach ($dokumenInput as $index => $dokumen) {
                $dokumenName = 'dokumen-' . $jenisDokumen[$index] . '-pengajuan-' . $lowongan_id . '-' . Auth::user()->username . '.pdf';
                $dokumen->storeAs('public/dokumen/mahasiswa/', $dokumenName);

                DokumenPengajuan::create([
                    'pengajuan_id' => $pengajuanMagang->pengajuan_id,
                    'path_file' => $dokumenName,
                    'jenis_dokumen' => $jenisDokumen[$index],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Pengajuan magang berhasil dikirim.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $th]);
        }
    }
}
