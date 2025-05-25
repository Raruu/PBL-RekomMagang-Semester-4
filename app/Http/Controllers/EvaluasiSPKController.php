<?php

namespace App\Http\Controllers;

use App\Models\BobotSPK;
use App\Models\KeahlianLowongan;
use App\Models\ProfilMahasiswa;
use App\Services\LocationService;
use App\Services\SPKService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EvaluasiSPKController extends Controller
{
    public function index()
    {
        $bobotSpk = BobotSPK::pluck('bobot', 'jenis_bobot')->toArray();
        return view('admin.spk.edit-bobot', ['spk' => $bobotSpk]);
    }

    public function spk(Request $request)
    {
        if ($request->ajax()) {
            $weights = [
                'IPK' => $request->input('bobot_ipk'),
                'keahlian' => $request->input('bobot_skill'),
                'pengalaman' => $request->input('bobot_pengalaman'),
                'jarak' => $request->input('bobot_jarak'),
                'posisi' => $request->input('bobot_posisi'),
            ];
            $score = SPKService::getRecommendations(84, $weights);
            return DataTables::of($score)
                ->addIndexColumn()
                ->addColumn('lowongan_id', function ($row) {
                    return $row['lowongan']->lowongan_id;
                })
                ->addColumn('skor', function ($row) {
                    return number_format($row['score'], 4);
                })
                ->addColumn('judul', function ($row) {
                    return $row['lowongan']->judul_lowongan;
                })
                ->make(true);
        }
        $bobotSpk = BobotSPK::pluck('bobot', 'jenis_bobot')->toArray();
        return view('admin.spk.edit-bobot', ['spk' => $bobotSpk]);
    }

    public function lowongan(Request $request)
    {
        $weights = [
            'IPK' => $request->input('bobot_ipk'),
            'keahlian' => $request->input('bobot_skill'),
            'pengalaman' => $request->input('bobot_pengalaman'),
            'jarak' => $request->input('bobot_jarak'),
            'posisi' => $request->input('bobot_posisi'),
        ];
        $lowonganMagang =  collect(SPKService::getRecommendations(84, $weights));
        $lowonganMagang = $lowonganMagang->firstWhere('lowongan.lowongan_id', $request->input('lowongan_id'));
        $lowongan = $lowonganMagang['lowongan'];
        $score = $lowonganMagang['score'];

        $lokasi = $lowongan->lokasi;
        $preferensiLokasi = ProfilMahasiswa::find(85)->preferensiMahasiswa->lokasi;
        $diff = date_diff(date_create(date('Y-m-d')), date_create($lowongan->batas_pendaftaran));

        return view('admin.spk.detail-lowongan', [
            'lowongan' => $lowongan,
            'tingkat_kemampuan' => KeahlianLowongan::TINGKAT_KEMAMPUAN,
            'score' => $score,
            'lokasi' => $lokasi,
            'jarak' => LocationService::haversineDistance(
                $lokasi->latitude,
                $lokasi->longitude,
                $preferensiLokasi->latitude,
                $preferensiLokasi->longitude
            ),
            'days' => $diff->format('%r%a'),
        ]);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'bobot_ipk' => 'required',
                'bobot_skill' => 'required',
                'bobot_pengalaman' => 'required',
                'bobot_jarak' => 'required',
                'bobot_posisi' => 'required',
            ]);

            $totalBobot = $request->input('bobot_ipk') +
                $request->input('bobot_skill') +
                $request->input('bobot_pengalaman') +
                $request->input('bobot_jarak') +
                $request->input('bobot_posisi');


            if ($totalBobot > 1.0) {
                return response()->json(['message' => 'Max Total Bobot adalah 1'], 422);
            }

            if ($validator->fails()) {
                return response()->json(['message' => 'Data tidak lengkap'], 422);
            }

            $bobot = $request->input('bobot');

            BobotSPK::updateOrCreate(['jenis_bobot' => 'IPK'], ['bobot' => $request->input('bobot_ipk')]);
            BobotSPK::updateOrCreate(['jenis_bobot' => 'keahlian'], ['bobot' => $request->input('bobot_skill')]);
            BobotSPK::updateOrCreate(['jenis_bobot' => 'pengalaman'], ['bobot' => $request->input('bobot_pengalaman')]);
            BobotSPK::updateOrCreate(['jenis_bobot' => 'jarak'], ['bobot' => $request->input('bobot_jarak')]);
            BobotSPK::updateOrCreate(['jenis_bobot' => 'posisi'], ['bobot' => $request->input('bobot_posisi')]);

            DB::commit();
            return response()->json(['message' => 'Bobot berhasil diperbarui.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
