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
            ['nama_program' => 'Teknik Informatika', 'deskripsi' => 'Program studi yang mempelajari tentang komputasi dan pemrograman'],
            ['nama_program' => 'Sistem Informasi', 'deskripsi' => 'Program studi yang menggabungkan teknologi informasi dan bisnis'],
            ['nama_program' => 'Teknik Komputer', 'deskripsi' => 'Program studi yang fokus pada hardware dan jaringan komputer'],
        ];
        DB::table('program_studi')->insert($programStudi);

        // Lokasi
        $lokasi = [
            ['alamat' => 'Jl. Pendidikan No. 1, Jakarta', 'latitude' => -6.200000, 'longitude' => 106.816666],
            ['alamat' => 'Jl. Teknologi No. 5, Bandung', 'latitude' => -6.914744, 'longitude' => 107.609810],
            ['alamat' => 'Jl. Industri No. 10, Surabaya', 'latitude' => -7.257472, 'longitude' => 112.752088],
        ];
        DB::table('lokasi')->insert($lokasi);

        // Kategori Keahlian
        $kategoriKeahlian = [
            ['nama_kategori' => 'Pemrograman', 'deskripsi' => 'Kategori keahlian untuk Pemrograman'],
            ['nama_kategori' => 'Desain', 'deskripsi' => 'Kategori keahlian untuk Desain'],
            ['nama_kategori' => 'Jaringan', 'deskripsi' => 'Kategori keahlian untuk Jaringan'],
            ['nama_kategori' => 'Basis Data', 'deskripsi' => 'Kategori keahlian untuk Basis Data'],
            ['nama_kategori' => 'Kecerdasan Buatan', 'deskripsi' => 'Kategori keahlian untuk Kecerdasan Buatan'],
        ];
        DB::table('kategori_keahlian')->insert($kategoriKeahlian);

        // Keahlian
        $keahlian = [
            ['nama_keahlian' => 'Pemrograman Web', 'kategori_id' => 1, 'deskripsi' => 'Keahlian dalam bidang Pemrograman Web'],
            ['nama_keahlian' => 'Pemrograman Mobile', 'kategori_id' => 1, 'deskripsi' => 'Keahlian dalam bidang Pemrograman Mobile'],
            ['nama_keahlian' => 'UI/UX Design', 'kategori_id' => 2, 'deskripsi' => 'Keahlian dalam bidang UI/UX Design'],
            ['nama_keahlian' => 'Desain Grafis', 'kategori_id' => 2, 'deskripsi' => 'Keahlian dalam bidang Desain Grafis'],
            ['nama_keahlian' => 'Administrasi Jaringan', 'kategori_id' => 3, 'deskripsi' => 'Keahlian dalam bidang Administrasi Jaringan'],
            ['nama_keahlian' => 'Keamanan Jaringan', 'kategori_id' => 3, 'deskripsi' => 'Keahlian dalam bidang Keamanan Jaringan'],
            ['nama_keahlian' => 'MySQL', 'kategori_id' => 4, 'deskripsi' => 'Keahlian dalam bidang MySQL'],
            ['nama_keahlian' => 'MongoDB', 'kategori_id' => 4, 'deskripsi' => 'Keahlian dalam bidang MongoDB'],
            ['nama_keahlian' => 'Machine Learning', 'kategori_id' => 5, 'deskripsi' => 'Keahlian dalam bidang Machine Learning'],
            ['nama_keahlian' => 'Computer Vision', 'kategori_id' => 5, 'deskripsi' => 'Keahlian dalam bidang Computer Vision'],
        ];
        DB::table('keahlian')->insert($keahlian);
    }
}
