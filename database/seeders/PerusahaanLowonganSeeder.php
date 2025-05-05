<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerusahaanLowonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Perusahaan
        $perusahaan = [
            [
                'lokasi_id' => 1,
                'nama_perusahaan' => 'PT. Teknologi Maju',
                'bidang_industri' => 'Teknologi',
                'website' => 'https://teknologimaju.com',
                'kontak_email' => 'hrd@teknologimaju.com',
                'kontak_telepon' => '0211234567',
                'is_active' => 1
            ],
            [
                'lokasi_id' => 2,
                'nama_perusahaan' => 'PT. Solusi Digital',
                'bidang_industri' => 'IT Konsultan',
                'website' => 'https://solusidigital.co.id',
                'kontak_email' => 'hrd@solusidigital.co.id',
                'kontak_telepon' => '0227654321',
                'is_active' => 1
            ],
            [
                'lokasi_id' => 3,
                'nama_perusahaan' => 'PT. Kreasi Desain',
                'bidang_industri' => 'Desain',
                'website' => 'https://kreasidesain.com',
                'kontak_email' => 'hrd@kreasidesain.com',
                'kontak_telepon' => '0319876543',
                'is_active' => 1
            ],
            [
                'lokasi_id' => 1,
                'nama_perusahaan' => 'PT. Data Analytics',
                'bidang_industri' => 'Big Data',
                'website' => 'https://dataanalytics.id',
                'kontak_email' => 'hrd@dataanalytics.id',
                'kontak_telepon' => '0215678912',
                'is_active' => 1
            ],
            [
                'lokasi_id' => 2,
                'nama_perusahaan' => 'PT. Jaringan Nusantara',
                'bidang_industri' => 'Telekomunikasi',
                'website' => 'https://jaringannusantara.co.id',
                'kontak_email' => 'hrd@jaringannusantara.co.id',
                'kontak_telepon' => '0223456789',
                'is_active' => 1
            ],
        ];
        foreach ($perusahaan as $p) {
            DB::table('perusahaan')->insertGetId($p);
        }

        // Periode Magang
        $periode = [
            ['nama_periode' => 'Magang Semester Genap 2023', 'tanggal_mulai' => '2023-02-01', 'tanggal_selesai' => '2023-06-30'],
            ['nama_periode' => 'Magang Semester Ganjil 2023', 'tanggal_mulai' => '2023-08-01', 'tanggal_selesai' => '2023-12-30'],
            ['nama_periode' => 'Magang Semester Genap 2024', 'tanggal_mulai' => '2024-02-01', 'tanggal_selesai' => '2024-06-30'],
            ['nama_periode' => 'Magang Semester Ganjil 2024', 'tanggal_mulai' => '2024-08-01', 'tanggal_selesai' => '2024-12-30'],
        ];
        foreach ($periode as $p) {
            DB::table('periode_magang')->insertGetId($p);
        }

        // Lowongan Magang
        $lowongan = [
            [
                'perusahaan_id' => 1,
                'periode_id' => 1,
                'lokasi_id' => 1,
                'judul_posisi' => 'Frontend Developer',
                'deskripsi' => 'Mencari mahasiswa magang untuk pengembangan antarmuka pengguna',
                'persyaratan' => "Mahasiswa semester 4-8\nPengalaman dengan HTML/CSS/JS\nMengenal framework frontend",
                'kuota' => 3,
                'opsi_remote' => 1,
                'tanggal_mulai' => '2023-02-15',
                'tanggal_selesai' => '2023-06-15',
                'batas_pendaftaran' => '2023-01-31',
                'is_active' => 1
            ],          
        ];

        foreach ($lowongan as $low) {
            $lowonganId = DB::table('lowongan_magang')->insertGetId($low);

            // Keahlian Lowongan
            $keahlianLowongan = [
                ['lowongan_id' => $lowonganId, 'keahlian_id' => rand(1, 5), 'kemampuan_minimum' => 'menengah'],
                ['lowongan_id' => $lowonganId, 'keahlian_id' => rand(1, 5), 'kemampuan_minimum' => 'pemula'],
            ];
            DB::table('keahlian_lowongan')->insert($keahlianLowongan);
        }
    }
}
