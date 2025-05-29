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
        $mahasiswa = DB::table('profil_mahasiswa')->pluck('mahasiswa_id');        
        $lowongan = DB::table('lowongan_magang')->pluck('lowongan_id')->toArray();

        foreach ($mahasiswa as $mhsId) {
            $jumlahPengajuan = rand(1, 3);
            $jumlahPengajuan = min($jumlahPengajuan, count($lowongan));
            $lowonganTerpilih = array_slice($lowongan, rand(0, count($lowongan) - 1), $jumlahPengajuan);

            foreach ($lowonganTerpilih as $lowId) {
                $status = $statuses[rand(0, 3)];
                $tanggalPengajuan = Carbon::parse(now()->format('Y-m-d'))->subYears(rand(0, date('Y') - 2015))->subDays(rand(1, 30));

                $lowonganId = $lowId;
                $pengajuanId = DB::table('pengajuan_magang')->insertGetId([
                    'mahasiswa_id' => $mhsId,
                    'lowongan_id' => $lowonganId,
                    'dosen_id' => null,
                    'tanggal_pengajuan' => $tanggalPengajuan,
                    'status' => $status,
                    'catatan_admin' => $status == 'ditolak' ? 'Kuota magang sudah penuh' : null,
                    'catatan_mahasiswa' => rand(0, 1) ? 'Saya sangat tertarik dengan posisi ini' : null,
                    'tanggal_mulai' => $status != 'menunggu' ? $tanggalPengajuan->addDays(7)->format('Y-m-d') : null,
                    'tanggal_selesai' => $status != 'menunggu' ? $tanggalPengajuan->addDays(90)->format('Y-m-d') : null,
                    'file_sertifikat' => $status == 'selesai' ? 'sertifikat/sertifikat_' . $mhsId . '_' . $lowonganId . '.pdf' : null,
                    'created_at' => $tanggalPengajuan,
                    'updated_at' => $tanggalPengajuan,
                ]);

                // Dokumen Pengajuan
                $dokumen = [
                    [
                        'pengajuan_id' => $pengajuanId,
                        'jenis_dokumen' => 'CV',
                        'path_file' => 'dokumen/cv_' . $mhsId . '.pdf',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'pengajuan_id' => $pengajuanId,
                        'jenis_dokumen' => 'Transkrip',
                        'path_file' => 'dokumen/transkrip_' . $mhsId . '.pdf',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'pengajuan_id' => $pengajuanId,
                        'jenis_dokumen' => 'Surat Rekomendasi',
                        'path_file' => 'dokumen/surat_rekomendasi_' . $mhsId . '.pdf',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ];

                foreach ($dokumen as $doc) {
                    DB::table('dokumen_pengajuan')->insert($doc);
                }

                // Log Aktivitas (hanya untuk yang disetujui/selesai)
                if (in_array($status, ['disetujui', 'selesai'])) {
                    for ($i = 0; $i < rand(5, 10); $i++) {
                        $logDate = $tanggalPengajuan->copy()->addDays($i * 7);
                        DB::table('log_aktivitas')->insert([
                            'pengajuan_id' => $pengajuanId,
                            'tanggal_log' => $logDate->format('Y-m-d'),
                            'aktivitas' => $this->getRandomActivity(),
                            'kendala' => rand(0, 1) ? $this->getRandomChallenge() : null,
                            'solusi' => rand(0, 1) ? $this->getRandomSolution() : null,
                            'jam_kegiatan' => Carbon::createFromFormat('H:i', sprintf('%02d:%02d', rand(8, 16), rand(0, 59)))->format('H:i'),
                            'created_at' => $logDate,
                            'updated_at' => $logDate,
                        ]);
                    }
                }

                // Feedback (hanya untuk yang selesai)
                if ($status == 'selesai') {
                    DB::table('feedback_mahasiswa')->insert([
                        'pengajuan_id' => $pengajuanId,
                        'rating' => rand(3, 5),
                        'komentar' => $this->getRandomFeedback(),
                        'pengalaman_belajar' => $this->getRandomLearningExperience(),
                        'kendala' => $this->getRandomChallenge(),
                        'saran' => $this->getRandomSuggestion(),
                        'created_at' => $tanggalPengajuan->addDays(90),
                        'updated_at' => $tanggalPengajuan->addDays(90),
                    ]);
                }
            }
        }
    }
}
