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
            [
                'nama_kategori' => 'Pengembangan Game',
                'deskripsi' => 'Kategori keahlian untuk Pengembangan Game',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Analisis Data',
                'deskripsi' => 'Kategori keahlian untuk Analisis Data',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('kategori_keahlian')->insert($kategoriKeahlian);

        // Keahlian
        $keahlian = [
            // Pemrograman (1)
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
                'nama_keahlian' => 'Python',
                'kategori_id' => 1,
                'deskripsi' => 'Keahlian dalam pemrograman Python',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'Java',
                'kategori_id' => 1,
                'deskripsi' => 'Keahlian dalam pemrograman Java',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'JavaScript',
                'kategori_id' => 1,
                'deskripsi' => 'Keahlian dalam pemrograman JavaScript',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'HTML CSS',
                'kategori_id' => 1,
                'deskripsi' => 'Keahlian dalam pemrograman HTML CSS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'React',
                'kategori_id' => 1,
                'deskripsi' => 'Keahlian dalam pemrograman React',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'PHP',
                'kategori_id' => 1,
                'deskripsi' => 'Keahlian dalam pemrograman PHP',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Desain (2)
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
                'nama_keahlian' => 'Adobe Photoshop',
                'kategori_id' => 2,
                'deskripsi' => 'Keahlian dalam menggunakan Adobe Photoshop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'Adobe Illustrator',
                'kategori_id' => 2,
                'deskripsi' => 'Keahlian dalam menggunakan Adobe Illustrator',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Jaringan (3)
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
                'nama_keahlian' => 'Cisco Networking',
                'kategori_id' => 3,
                'deskripsi' => 'Keahlian dalam jaringan Cisco',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Basis Data (4)
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
                'nama_keahlian' => 'SQL Server',
                'kategori_id' => 4,
                'deskripsi' => 'Keahlian dalam Microsoft SQL Server',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kecerdasan Buatan (5)
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
            [
                'nama_keahlian' => 'Natural Language Processing',
                'kategori_id' => 5,
                'deskripsi' => 'Keahlian dalam pemrosesan bahasa alami',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Pengembangan Game (6)
            [
                'nama_keahlian' => 'Unity',
                'kategori_id' => 6,
                'deskripsi' => 'Keahlian dalam pengembangan game menggunakan Unity',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'Unreal Engine',
                'kategori_id' => 6,
                'deskripsi' => 'Keahlian dalam pengembangan game menggunakan Unreal Engine',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Analisis Data (7)
            [
                'nama_keahlian' => 'Data Visualization',
                'kategori_id' => 7,
                'deskripsi' => 'Keahlian dalam visualisasi data',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'Tableau',
                'kategori_id' => 7,
                'deskripsi' => 'Keahlian dalam menggunakan Tableau',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('keahlian')->insert($keahlian);
    }
}
