<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LowonganMagang;

class LowonganMagangSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['lowongan_id' => 1, 'judul_lowongan' => 'Magang Web Developer Laravel di Surabaya', 'judul_posisi' => 'Web Developer'],
            ['lowongan_id' => 2, 'judul_lowongan' => 'Magang UI/UX Designer untuk Aplikasi Mobile', 'judul_posisi' => 'UI/UX Designer'],
            ['lowongan_id' => 3, 'judul_lowongan' => 'Magang Mobile Developer Flutter', 'judul_posisi' => 'Mobile Developer'],
            ['lowongan_id' => 4, 'judul_lowongan' => 'Magang Data Analyst dengan Python & Excel', 'judul_posisi' => 'Data Analyst'],
            ['lowongan_id' => 5, 'judul_lowongan' => 'Magang Software Tester (QA Engineer)', 'judul_posisi' => 'Software Tester'],
            ['lowongan_id' => 6, 'judul_lowongan' => 'Magang IT Support untuk Infrastruktur Jaringan', 'judul_posisi' => 'IT Support'],
            ['lowongan_id' => 7, 'judul_lowongan' => 'Magang Frontend Developer dengan ReactJS', 'judul_posisi' => 'Frontend Developer'],
            ['lowongan_id' => 8, 'judul_lowongan' => 'Magang Digital Marketing Produk Teknologi', 'judul_posisi' => 'Digital Marketer'],
        ];

        foreach ($data as $item) {
            LowonganMagang::where('lowongan_id', $item['lowongan_id'])
                ->update([
                    'judul_lowongan' => $item['judul_lowongan'],
                    'judul_posisi' => $item['judul_posisi'],
                ]);
        }
    }
}
