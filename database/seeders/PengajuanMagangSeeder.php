<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengajuanMagangSeeder extends Seeder
{
    protected function getRandomActivity()
    {
        $activities = [
            'Mempelajari framework baru untuk pengembangan aplikasi',
            'Mengikuti sesi mentoring dengan senior developer',
            'Mengembangkan fitur baru untuk aplikasi internal',
            'Melakukan testing dan debugging kode',
            'Menghadiri meeting tim untuk membahas progress proyek',
            'Membuat dokumentasi teknis',
            'Melakukan penelitian untuk solusi teknis'
        ];
        return $activities[array_rand($activities)];
    }

    protected function getRandomChallenge()
    {
        $challenges = [
            'Kesulitan memahami library baru',
            'Kendala dalam integrasi API',
            'Perbedaan waktu kerja dengan tim',
            'Bug yang sulit diidentifikasi',
            'Keterbatasan akses ke beberapa resource'
        ];
        return $challenges[array_rand($challenges)];
    }

    protected function getRandomSolution()
    {
        $solutions = [
            'Diskusi dengan mentor dan tim',
            'Mencari referensi dari dokumentasi resmi',
            'Melakukan pair programming',
            'Menggunakan tools debugging',
            'Meminta bantuan dari senior developer'
        ];
        return $solutions[array_rand($solutions)];
    }

    protected function getRandomFeedback()
    {
        $feedbacks = [
            'Pengalaman magang yang sangat berharga',
            'Tim yang sangat supportive dan membantu',
            'Proyek yang menantang tetapi bermanfaat',
            'Lingkungan kerja yang kondusif untuk belajar',
            'Kesempatan untuk mengerjakan proyek nyata'
        ];
        return $feedbacks[array_rand($feedbacks)];
    }

    protected function getRandomLearningExperience()
    {
        $experiences = [
            'Saya belajar banyak tentang pengembangan perangkat lunak profesional',
            'Mendapatkan pengalaman langsung dalam siklus pengembangan produk',
            'Meningkatkan keterampilan teknis dan kolaborasi tim',
            'Memahami bagaimana teori diaplikasikan dalam proyek nyata',
            'Mengembangkan pola pikir pemecahan masalah yang lebih baik'
        ];
        return $experiences[array_rand($experiences)];
    }

    protected function getRandomSuggestion()
    {
        $suggestions = [
            'Lebih banyak sesi mentoring teknis',
            'Proyek yang lebih beragam',
            'Keterlibatan lebih awal dalam proses pengambilan keputusan',
            'Akses ke lebih banyak resource pembelajaran',
            'Feedback yang lebih terstruktur'
        ];
        return $suggestions[array_rand($suggestions)];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['menunggu', 'disetujui', 'ditolak', 'selesai'];

        // Ambil data mahasiswa, dosen, dan lowongan
        $mahasiswa = DB::table('profil_mahasiswa')->where('nim', '!=', '0000000000')->pluck('mahasiswa_id')->toArray();
        $lowongan = array_filter(DB::table('lowongan_magang')->pluck('lowongan_id')->toArray(), function ($value) {
            return !in_array($value, [16, 17]);
        });

        foreach ($mahasiswa as $mhsId) {
            // Test #1
            $staticLowId = 16;

            $pengajuanId = DB::table('pengajuan_magang')->insertGetId([
                'mahasiswa_id' => $mhsId,
                'lowongan_id' => $staticLowId,
                'status' => $statuses[0],
                'tanggal_pengajuan' => now(),
                'catatan_admin' => 'Pengajuan disetujui',
                'catatan_mahasiswa' => 'Saya tertarik dengan magang ini, saya ingin mengikuti magang ini',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $dokumenLowongan = DB::table('persyaratan_magang')->where('lowongan_id', $staticLowId)->first();
            DB::table('dokumen_pengajuan')->insert([
                'pengajuan_id' => $pengajuanId,
                'jenis_dokumen' => 'CV',
                'path_file' => 'placeholder_cv.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            if ($dokumenLowongan) {
                $dokumenLowongan = explode(';', $dokumenLowongan->dokumen_persyaratan);
                foreach ($dokumenLowongan as $dokumen) {
                    if (empty($dokumen)) continue;
                    DB::table('dokumen_pengajuan')->insert([
                        'pengajuan_id' => $pengajuanId,
                        'jenis_dokumen' => $dokumen,
                        'path_file' => 'placeholder_dokumen.pdf',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Test #3
            $staticLowId = 18;

            $pengajuanId = DB::table('pengajuan_magang')->insertGetId([
                'mahasiswa_id' => $mhsId,
                'dosen_id' => 3,
                'lowongan_id' => $staticLowId,
                'status' => $statuses[1],
                'tanggal_pengajuan' => '2024-02-10',
                'catatan_admin' => 'Pengajuan disetujui',
                'catatan_mahasiswa' => 'Saya benar-benar tertarik dengan magang ini',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $dokumenLowongan = DB::table('persyaratan_magang')->where('lowongan_id', $staticLowId)->first();
            DB::table('dokumen_pengajuan')->insert([
                'pengajuan_id' => $pengajuanId,
                'jenis_dokumen' => 'CV',
                'path_file' => 'placeholder_cv.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            if ($dokumenLowongan) {
                $dokumenLowongan = explode(';', $dokumenLowongan->dokumen_persyaratan);
                foreach ($dokumenLowongan as $dokumen) {
                    if (empty($dokumen)) continue;
                    DB::table('dokumen_pengajuan')->insert([
                        'pengajuan_id' => $pengajuanId,
                        'jenis_dokumen' => $dokumen,
                        'path_file' => 'placeholder_dokumen.pdf',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // for ($outerI = 0; $outerI < 20; $outerI++) {
        //     $mhsId = $mahasiswa[array_rand($mahasiswa)];
        //     // $jumlahPengajuan = rand(1, 3);
        //     // $jumlahPengajuan = min($jumlahPengajuan, count($lowongan));
        //     // $lowonganTerpilih = array_slice($lowongan, rand(0, count($lowongan) - 1), $jumlahPengajuan);
        //     $lowonganTerpilih = array_rand(array_flip($lowongan), 3);

        //     foreach ($lowonganTerpilih as $lowId) {
        //         $status = $statuses[rand(0, 3)];
        //         $tanggalPengajuan = Carbon::parse(now()->format('Y-m-d'))->subYears(rand(0, date('Y') - 2015))->subDays(rand(1, 30));

        //         $pengajuanId = DB::table('pengajuan_magang')->insertGetId([
        //             'mahasiswa_id' => $mhsId,
        //             'lowongan_id' => $lowId,
        //             'dosen_id' => $status == 'menunggu' || $status == 'ditolak' ? null : DB::table('profil_dosen')->inRandomOrder()->first()->dosen_id,
        //             'tanggal_pengajuan' => $tanggalPengajuan,
        //             'status' => $status,
        //             'catatan_admin' => $status == 'ditolak' ? 'Anda tidak memenuhi persyaratan magang' : null,
        //             'catatan_mahasiswa' => rand(0, 1) ? 'Saya sangat tertarik dengan posisi ini' : null,
        //             // 'tanggal_mulai' => $status != 'menunggu' ? $tanggalPengajuan->addDays(7)->format('Y-m-d') : null,
        //             // 'tanggal_selesai' => $status != 'menunggu' ? $tanggalPengajuan->addDays(90)->format('Y-m-d') : null,
        //             'file_sertifikat' => $status == 'selesai' ? 'placeholder_sertif.pdf' : null,
        //             'created_at' => $tanggalPengajuan,
        //             'updated_at' => $tanggalPengajuan,
        //         ]);

        //         // Dokumen Pengajuan
        //         $dokumenLowongan = DB::table('persyaratan_magang')->where('lowongan_id', $lowId)->first();
        //         DB::table('dokumen_pengajuan')->insert([
        //             'pengajuan_id' => $pengajuanId,
        //             'jenis_dokumen' => 'CV',
        //             'path_file' => 'placeholder_cv.pdf',
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //         if ($dokumenLowongan) {
        //             $dokumenLowongan = explode(';', $dokumenLowongan->dokumen_persyaratan);
        //             foreach ($dokumenLowongan as $dokumen) {
        //                 if (empty($dokumen)) continue;
        //                 DB::table('dokumen_pengajuan')->insert([
        //                     'pengajuan_id' => $pengajuanId,
        //                     'jenis_dokumen' => $dokumen,
        //                     'path_file' => 'placeholder_dokumen.pdf',
        //                     'created_at' => now(),
        //                     'updated_at' => now(),
        //                 ]);
        //             }
        //         }

        //         // Log Aktivitas (hanya untuk yang disetujui/selesai)
        //         if (in_array($status, ['disetujui', 'selesai'])) {
        //             for ($i = 0; $i < rand(5, 10); $i++) {
        //                 $logDate = $tanggalPengajuan->copy()->addDays($i * 7);
        //                 DB::table('log_aktivitas')->insert([
        //                     'pengajuan_id' => $pengajuanId,
        //                     'tanggal_log' => $logDate->format('Y-m-d'),
        //                     'aktivitas' => $this->getRandomActivity(),
        //                     'kendala' => rand(0, 1) ? $this->getRandomChallenge() : null,
        //                     'solusi' => rand(0, 1) ? $this->getRandomSolution() : null,
        //                     'jam_kegiatan' => Carbon::createFromFormat('H:i', sprintf('%02d:%02d', rand(8, 16), rand(0, 59)))->format('H:i'),
        //                     'created_at' => $logDate,
        //                     'updated_at' => $logDate,
        //                 ]);
        //             }
        //         }

        //         // Feedback (hanya untuk yang selesai)
        //         if ($status == 'selesai') {
        //             DB::table('feedback_mahasiswa')->insert([
        //                 'pengajuan_id' => $pengajuanId,
        //                 'rating' => rand(3, 5),
        //                 'komentar' => $this->getRandomFeedback(),
        //                 'pengalaman_belajar' => $this->getRandomLearningExperience(),
        //                 'kendala' => $this->getRandomChallenge(),
        //                 'saran' => $this->getRandomSuggestion(),
        //                 'created_at' => $tanggalPengajuan->addDays(90),
        //                 'updated_at' => $tanggalPengajuan->addDays(90),
        //             ]);
        //         }
        //     }
        // }
    }
}
