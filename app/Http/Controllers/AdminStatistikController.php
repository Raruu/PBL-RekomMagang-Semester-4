<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMagang;
use App\Models\PerusahaanMitra;
use App\Models\ProfilMahasiswa;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class AdminStatistikController extends Controller
{
    public function index()
    {
        $bidangIndustri = PerusahaanMitra::select('bidang_industri')->distinct()->pluck('bidang_industri')->toArray();

        return view('admin.statistik.index', ['bidangIndustri' => $bidangIndustri]);
    }

    public function getMagangVsTidak(Request $request)
    {
        $start = $request->start;
        $end = $request->end;

        $countsAcc = [];
        $countMhs = [];

        for ($year = $start; $year <= $end; $year++) {
            $countsAcc[$year] = ProfilMahasiswa::where('angkatan', $year)->whereHas('pengajuanMagang', function ($query) {
                $query->whereIn('status', ['selesai', 'disetujui']);
            })->count();

            $countMhs[$year] = ProfilMahasiswa::where('angkatan', $year)->count() - $countsAcc[$year];
        }

        return response()->json([
            'acc' => $countsAcc,
            'mhs' => $countMhs
        ]);
    }

    public function excelMagangVsTidak(Request $request)
    {
        $start = $request->start;
        $end = $request->end;

        $countsAcc = [];
        $countMhs = [];

        for ($year = $start; $year <= $end; $year++) {
            $countsAcc[$year] = ProfilMahasiswa::where('angkatan', $year)->whereHas('pengajuanMagang', function ($query) {
                $query->whereIn('status', ['selesai', 'disetujui']);
            })->count();

            $countMhs[$year] = ProfilMahasiswa::where('angkatan', $year)->count() - $countsAcc[$year];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tahun');
        $sheet->setCellValue('C1', 'Mendapat Magang');
        $sheet->setCellValue('D1', 'Tidak Mendapat Magang');

        $row = 2;
        foreach ($countsAcc as $year => $count) {
            $sheet->setCellValue('A' . $row, $row - 1);
            $sheet->setCellValue('B' . $row, $year);
            $sheet->setCellValue('C' . $row, $count);
            $sheet->setCellValue('D' . $row, $countMhs[$year]);
            $row++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'statistik_magang_vs_tidak' . date('d-m-Y H:i') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, dMY H:i:s') . 'GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    }

    public function getTrenPeminatanMahasiswa(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $tags = $request->tags;

        $counts = [];

        foreach ($tags as $tag) {
            for ($year = $start; $year <= $end; $year++) {
                $counts[$year][$tag] = PengajuanMagang::whereYear('tanggal_pengajuan', $year)->whereHas('lowonganMagang', function ($query) use ($tag) {
                    $query->whereHas('perusahaanMitra', function ($query) use ($tag) {
                        $query->where('bidang_industri', $tag);
                    });
                })->get()->count();
            }
        }

        return response()->json(['data' => $counts]);
    }

    public function excelTrenPeminatanMahasiswa(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $tags = explode(',', $request->tags);
        $tags = array_map(function ($tag) {
            return str_replace('%20', ' ', $tag);
        }, $tags);
    
        $counts = [];

        foreach ($tags as $tag) {
            for ($year = $start; $year <= $end; $year++) {
                $counts[$year][$tag] = PengajuanMagang::whereYear('tanggal_pengajuan', $year)->whereHas('lowonganMagang', function ($query) use ($tag) {
                    $query->whereHas('perusahaanMitra', function ($query) use ($tag) {
                        $query->where('bidang_industri', $tag);
                    });
                })->get();
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tahun');
        $sheet->setCellValue('C1', 'Bidang Industri');
        $sheet->setCellValue('D1', 'Jumlah');
    
        $row = 2;
        foreach ($counts as $year => $yearCounts) {
            foreach ($yearCounts as $tag => $count) {
                $sheet->setCellValue('A' . $row, $row - 1);
                $sheet->setCellValue('B' . $row, $year);
                $sheet->setCellValue('C' . $row, $tag);
                $sheet->setCellValue('D' . $row, $count->count());
                $row++;
            }
        }
    
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'statistik_tren_peminatan_mahasiswa' . date('d-m-Y H:i') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, dMY H:i:s') . 'GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    }
}
