<?php

namespace App\Http\Controllers;

use App\Models\LowonganMagang;
use Illuminate\Http\Request;

class AdminManajemenPeriodeController extends Controller
{
    public function index()
    {
        $lowonganList = LowonganMagang::with('perusahaanMitra')->get();

        // Pisahkan menjadi dua koleksi
        $lowonganBelumAdaTanggal = $lowonganList->filter(function ($item) {
            return empty($item->tanggal_mulai) || empty($item->tanggal_selesai);
        });
        $lowonganSudahAdaTanggal = $lowonganList->filter(function ($item) {
            return !empty($item->tanggal_mulai) && !empty($item->tanggal_selesai);
        });

        if(request()->ajax()) {
            // Tentukan jenis data yang diminta (belum/sudah)
            $tipe = request('tipe');
            if ($tipe === 'belum') {
                $data = $lowonganBelumAdaTanggal;
            } elseif ($tipe === 'sudah') {
                $data = $lowonganSudahAdaTanggal;
            } else {
                $data = $lowonganList;
            }
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('judul_lowongan', function ($row) {
                    return $row->judul_lowongan;
                })
                ->addColumn('perusahaan', function ($row) {
                    return $row->perusahaanMitra ? $row->perusahaanMitra->nama_perusahaan : '-';
                })
                ->addColumn('tanggal_mulai', function ($row) {
                    return $row->tanggal_mulai ? date('d/m/Y', strtotime($row->tanggal_mulai)) : '-';
                })
                ->addColumn('tanggal_selesai', function ($row) {
                    return $row->tanggal_selesai ? date('d/m/Y', strtotime($row->tanggal_selesai)) : '-';
                })
                ->addColumn('action', function ($row) {
                    $mulai = $row->tanggal_mulai ? date('Y-m-d', strtotime($row->tanggal_mulai)) : '';
                    $selesai = $row->tanggal_selesai ? date('Y-m-d', strtotime($row->tanggal_selesai)) : '';    
                    return '<button type="button" class="btn btn-warning btn-sm btn-edit-periode" 
                        data-id="'.$row->lowongan_id.'" 
                        data-mulai="'.$mulai.'" 
                        data-selesai="'.$selesai.'" 
                        title="Edit Periode">
                        <i class="fas fa-edit"></i>
                    </button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.magang.periode.index', [
            'lowonganBelumAdaTanggal' => $lowonganBelumAdaTanggal,
            'lowonganSudahAdaTanggal' => $lowonganSudahAdaTanggal
        ]);
    }

    public function edit($id)
    {
        $lowongan = LowonganMagang::with('perusahaanMitra')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => [
                'judul_lowongan' => $lowongan->judul_lowongan,
                'perusahaan' => $lowongan->perusahaanMitra ? $lowongan->perusahaanMitra->nama_perusahaan : '-',
                'tanggal_mulai' => $lowongan->tanggal_mulai,
                'tanggal_selesai' => $lowongan->tanggal_selesai,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $lowongan = LowonganMagang::findOrFail($id);
        $lowongan->tanggal_mulai = $request->tanggal_mulai;
        $lowongan->tanggal_selesai = $request->tanggal_selesai;
        $lowongan->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Periode lowongan berhasil diperbarui.',
                'data' => [
                    'tanggal_mulai' => $lowongan->tanggal_mulai,
                    'tanggal_selesai' => $lowongan->tanggal_selesai,
                ]
            ]);
        }

        return redirect()->route('admin.manajemen_periode.index')
            ->with('success', 'Periode lowongan berhasil diperbarui.');
    }
}
