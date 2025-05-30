<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Program Studi
        $programStudi = [
            [
                'nama_program' => 'Teknik Informatika',
                'deskripsi' => 'Program studi yang mempelajari tentang komputasi dan pemrograman',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_program' => 'Sistem Informasi Bisnis',
                'deskripsi' => 'Program studi yang menggabungkan teknologi informasi dan bisnis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_program' => 'Teknik Komputer',
                'deskripsi' => 'Program studi yang fokus pada hardware dan jaringan komputer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_program' => 'Teknik Elektro',
                'deskripsi' => 'Program studi yang mempelajari tentang listrik dan elektronik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_program' => 'Teknik Mesin',
                'deskripsi' => 'Program studi yang mempelajari tentang desain, konstruksi, dan pengoperasian mesin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('program_studi')->insert($programStudi);

        // Kategori Keahlian
        $kategoriKeahlian = [
            [
                'nama_kategori' => 'Pemrograman',
                'deskripsi' => 'Kategori keahlian untuk Pemrograman',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Desain',
                'deskripsi' => 'Kategori keahlian untuk Desain',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Jaringan',
                'deskripsi' => 'Kategori keahlian untuk Jaringan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Basis Data',
                'deskripsi' => 'Kategori keahlian untuk Basis Data',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Kecerdasan Buatan',
                'deskripsi' => 'Kategori keahlian untuk Kecerdasan Buatan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('kategori_keahlian')->insert($kategoriKeahlian);

        // Keahlian
        $keahlian = [
            [
                'nama_keahlian' => 'Pemrograman Web',
                'kategori_id' => 1,
                'deskripsi' => 'Keahlian dalam bidang Pemrograman Web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'Pemrograman Mobile',
                'kategori_id' => 1,
                'deskripsi' => 'Keahlian dalam bidang Pemrograman Mobile',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'UI/UX Design',
                'kategori_id' => 2,
                'deskripsi' => 'Keahlian dalam bidang UI/UX Design',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'Desain Grafis',
                'kategori_id' => 2,
                'deskripsi' => 'Keahlian dalam bidang Desain Grafis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'Administrasi Jaringan',
                'kategori_id' => 3,
                'deskripsi' => 'Keahlian dalam bidang Administrasi Jaringan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'Keamanan Jaringan',
                'kategori_id' => 3,
                'deskripsi' => 'Keahlian dalam bidang Keamanan Jaringan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'MySQL',
                'kategori_id' => 4,
                'deskripsi' => 'Keahlian dalam bidang MySQL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'MongoDB',
                'kategori_id' => 4,
                'deskripsi' => 'Keahlian dalam bidang MongoDB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'Machine Learning',
                'kategori_id' => 5,
                'deskripsi' => 'Keahlian dalam bidang Machine Learning',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'Computer Vision',
                'kategori_id' => 5,
                'deskripsi' => 'Keahlian dalam bidang Computer Vision',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('keahlian')->insert($keahlian);
    }
}
