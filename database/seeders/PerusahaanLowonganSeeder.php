<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerusahaanLowonganSeeder extends Seeder
{
    protected function getJobRequirementDesc()
    {
        $requirements = [
            'Mahasiswa aktif semester 4-8',
            'Memiliki pengetahuan dasar pemrograman',
            'Bersedia belajar teknologi baru',
            'Dapat bekerja dalam tim',
            'Memiliki motivasi tinggi'
        ];
        return implode(";", array_intersect_key($requirements, array_flip(array_rand($requirements, mt_rand(3, 5)))));
    }

    protected function getJobDocumentRequirement()
    {
        $useDocument = mt_rand(0, 1) == 1;
        if (!$useDocument) {
            return null;
        }
        $documents = [
            'KTP',
            'Surat Keterangan Lulus',
            'Transkrip Nilai',
            'Surat Rekomendasi',
            'Ijazah',
            'Pas Foto',
            'Surat Keterangan Berbadan Sehat'
        ];

        $toReturn = [];
        for ($i = 0; $i < mt_rand(1, count($documents)); $i++) {
            $toReturn[] = $documents[mt_rand(0, count($documents) - 1)];
        }

        return count($toReturn) > 0 ? implode(';', $toReturn) : null;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bidangIndustri = [
            ['nama' => 'Teknologi', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'IT Konsultan', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Desain', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Big Data', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Telekomunikasi', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($bidangIndustri as $bidang) {
            DB::table('bidang_industri')->updateOrInsert(
                ['nama' => $bidang['nama']],
                $bidang
            );
        }

        // Perusahaan
        $perusahaan = [
            [
                'lokasi_id' => DB::table('lokasi')->insertGetId([
                    'alamat' => 'Jl. Pendidikan No. 1, Jakarta',
                    'latitude' => -6.200000,
                    'longitude' => 106.816666,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]),
                'nama_perusahaan' => 'PT. Teknologi Maju',
                'bidang_id' => DB::table('bidang_industri')->where('nama', 'Teknologi')->value('bidang_id'),
                'website' => 'https://teknologimaju.com',
                'kontak_email' => 'hrd@teknologimaju.com',
                'kontak_telepon' => '0211234567',
                'created_at' => now(),
                'updated_at' => now(),
                'is_active' => 1
            ],
            [
                'lokasi_id' => DB::table('lokasi')->insertGetId([
                    'alamat' => 'Jl. Pendidikan No. 2, Bandung',
                    'latitude' => -6.902000,
                    'longitude' => 107.618333,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]),
                'nama_perusahaan' => 'PT. Solusi Digital',
                'bidang_id' => DB::table('bidang_industri')->where('nama', 'IT Konsultan')->value('bidang_id'),
                'website' => 'https://solusidigital.co.id',
                'kontak_email' => 'hrd@solusidigital.co.id',
                'kontak_telepon' => '0227654321',
                'created_at' => now(),
                'updated_at' => now(),
                'is_active' => 1
            ],
            [
                'lokasi_id' => DB::table('lokasi')->insertGetId([
                    'alamat' => 'Jl. Pendidikan No. 3, Surabaya',
                    'latitude' => -7.274000,
                    'longitude' => 112.737500,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]),
                'nama_perusahaan' => 'PT. Kreasi Desain',
                'bidang_id' => DB::table('bidang_industri')->where('nama', 'Desain')->value('bidang_id'),
                'website' => 'https://kreasidesain.com',
                'kontak_email' => 'hrd@kreasidesain.com',
                'kontak_telepon' => '0319876543',
                'created_at' => now(),
                'updated_at' => now(),
                'is_active' => 1
            ],
            [
                'lokasi_id' => DB::table('lokasi')->insertGetId([
                    'alamat' => 'Jl. Pendidikan No. 4, Jakarta',
                    'latitude' => -6.214000,
                    'longitude' => 106.835000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]),
                'nama_perusahaan' => 'PT. Data Analytics',
                'bidang_id' => DB::table('bidang_industri')->where('nama', 'Big Data')->value('bidang_id'),
                'website' => 'https://dataanalytics.id',
                'kontak_email' => 'hrd@dataanalytics.id',
                'kontak_telepon' => '0215678912',
                'created_at' => now(),
                'updated_at' => now(),
                'is_active' => 1
            ],
            [
                'lokasi_id' => DB::table('lokasi')->insertGetId([
                    'alamat' => 'Jl. Pendidikan No. 5, Bandung',
                    'latitude' => -6.925000,
                    'longitude' => 107.637500,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]),
                'nama_perusahaan' => 'PT. Jaringan Nusantara',
                'bidang_id' => DB::table('bidang_industri')->where('nama', 'Telekomunikasi')->value('bidang_id'),
                'website' => 'https://jaringannusantara.co.id',
                'kontak_email' => 'hrd@jaringannusantara.co.id',
                'kontak_telepon' => '0223456789',
                'created_at' => now(),
                'updated_at' => now(),
                'is_active' => 1
            ],
        ];

        foreach ($perusahaan as $p) {
            DB::table('perusahaan')->insertGetId($p);
        }

        // Lowongan Magang
        $lowongan = [
            [
                'perusahaan_id' => 1,
                'lokasi_id' => DB::table('lokasi')->insertGetId([
                    'alamat' => 'Jl. Pendidikan No. 1, Jakarta',
                    'latitude' => -6.200000,
                    'longitude' => 106.816666,
                    'created_at' => now(),
                    'updated_at' => now()
                ]),
                'judul_lowongan' => 'Magang Frontend Developer',
                'judul_posisi' => 'Frontend Developer',
                'deskripsi' => 'Mencari mahasiswa magang untuk pengembangan antarmuka pengguna',
                'gaji' => 2000000.00,
                'kuota' => 3,
                'tipe_kerja_lowongan' => 'hybrid',
                'tanggal_mulai' => '2023-02-15',
                'tanggal_selesai' => '2023-06-15',
                'batas_pendaftaran' => '2023-01-31',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'perusahaan_id' => 1,
                'lokasi_id' => DB::table('lokasi')->insertGetId([
                    'alamat' => 'Jl. Pendidikan No. 2, Jakarta',
                    'latitude' => -6.210000,
                    'longitude' => 106.820000,
                    'created_at' => now(),
                    'updated_at' => now()
                ]),
                'judul_lowongan' => 'Magang UI/UX Designer',
                'judul_posisi' => 'UI/UX Designer',
                'deskripsi' => 'Mendesain antarmuka pengguna yang menarik dan fungsional',
                'gaji' => 1800000.00,
                'kuota' => 2,
                'tipe_kerja_lowongan' => 'remote',
                'tanggal_mulai' => '2023-02-20',
                'tanggal_selesai' => '2023-06-20',
                'batas_pendaftaran' => '2023-02-05',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'perusahaan_id' => 2,
                'lokasi_id' => DB::table('lokasi')->insertGetId([
                    'alamat' => 'Jl. Pendidikan No. 3, Surabaya',
                    'latitude' => -7.250000,
                    'longitude' => 112.750000,
                    'created_at' => now(),
                    'updated_at' => now()
                ]),
                'judul_lowongan' => 'Magang Data Analyst Intern',
                'judul_posisi' => 'Data Analyst Intern',
                'deskripsi' => 'Menganalisis data untuk mendukung keputusan bisnis',
                'gaji' => 2200000.00,
                'kuota' => 4,
                'tipe_kerja_lowongan' => 'hybrid',
                'tanggal_mulai' => '2023-03-01',
                'tanggal_selesai' => '2023-07-01',
                'batas_pendaftaran' => '2023-02-10',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'perusahaan_id' => 3,
                'lokasi_id' => DB::table('lokasi')->insertGetId([
                    'alamat' => 'Jl. Pendidikan No. 4, Bandung',
                    'latitude' => -6.950000,
                    'longitude' => 107.610000,
                    'created_at' => now(),
                    'updated_at' => now()
                ]),
                'judul_lowongan' => 'Magang Graphic Designer Intern',
                'judul_posisi' => 'Graphic Designer Intern',
                'deskripsi' => 'Membantu tim desain dalam proyek-proyek kreatif',
                'gaji' => 1500000.00,
                'kuota' => 2,
                'tipe_kerja_lowongan' => 'onsite',
                'tanggal_mulai' => '2023-04-01',
                'tanggal_selesai' => '2023-08-01',
                'batas_pendaftaran' => '2023-03-15',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'perusahaan_id' => 4,
                'lokasi_id' => DB::table('lokasi')->insertGetId([
                    'alamat' => 'Jl. Pendidikan No. 5, Jakarta',
                    'latitude' => -6.220000,
                    'longitude' => 106.830000,
                    'created_at' => now(),
                    'updated_at' => now()
                ]),
                'judul_lowongan' => 'Magang Data Scientist Intern',
                'judul_posisi' => 'Data Scientist Intern',
                'deskripsi' => 'Mengembangkan model analisis data untuk proyek-proyek besar',
                'gaji' => 3000000.00,
                'kuota' => 1,
                'tipe_kerja_lowongan' => 'hybrid',
                'tanggal_mulai' => '2023-05-01',
                'tanggal_selesai' => '2023-09-01',
                'batas_pendaftaran' => '2023-04-15',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'perusahaan_id' => 5,
                'lokasi_id' => DB::table('lokasi')->insertGetId([
                    'alamat' => 'Jl. Pendidikan No. 6, Surabaya',
                    'latitude' => -7.260000,
                    'longitude' => 112.760000,
                    'created_at' => now(),
                    'updated_at' => now()
                ]),
                'judul_lowongan' => 'Magang Network Engineer Intern',
                'judul_posisi' => 'Network Engineer Intern',
                'deskripsi' => 'Membantu dalam pengelolaan jaringan dan infrastruktur TI',
                'gaji' => 2400000.00,
                'kuota' => 3,
                'tipe_kerja_lowongan' => 'onsite',
                'tanggal_mulai' => '2023-06-01',
                'tanggal_selesai' => '2023-10-01',
                'batas_pendaftaran' => '2023-05-15',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'perusahaan_id' => 1,
                'lokasi_id' => DB::table('lokasi')->insertGetId([
                    'alamat' => 'Jl. Pendidikan No. 7, Jakarta',
                    'latitude' => -6.230000,
                    'longitude' => 106.840000,
                    'created_at' => now(),
                    'updated_at' => now()
                ]),
                'judul_lowongan' => 'Magang Software Engineer Intern',
                'judul_posisi' => 'Software Engineer Intern',
                'deskripsi' => 'Bergabung dengan tim pengembangan perangkat lunak',
                'gaji' => 2600000.00,
                'kuota' => 2,
                'tipe_kerja_lowongan' => 'hybrid',
                'tanggal_mulai' => '2023-07-01',
                'tanggal_selesai' => '2023-11-01',
                'batas_pendaftaran' => '2023-06-15',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'perusahaan_id' => 2,
                'lokasi_id' => DB::table('lokasi')->insertGetId([
                    'alamat' => 'Jl. Pendidikan No. 8, Surabaya',
                    'latitude' => -7.270000,
                    'longitude' => 112.770000,
                    'created_at' => now(),
                    'updated_at' => now()
                ]),
                'judul_lowongan' => 'Magang Backend Developer',
                'judul_posisi' => 'Backend Developer',
                'deskripsi' => 'Membangun API dan sistem backend',
                'gaji' => 2500000.00,
                'kuota' => 2,
                'tipe_kerja_lowongan' => 'onsite',
                'tanggal_mulai' => '2023-03-01',
                'tanggal_selesai' => '2023-07-31',
                'batas_pendaftaran' => '2023-02-15',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($lowongan as $low) {
            $lowonganId = DB::table('lowongan_magang')->insertGetId($low);

            // Persyaratan Magang
            DB::table('persyaratan_magang')->insert([
                'lowongan_id' => $lowonganId,
                'minimum_ipk' => rand(25, 35) / 10, // IPK 2.5 - 3.5
                'deskripsi_persyaratan' => $this->getJobRequirementDesc(),
                'dokumen_persyaratan' => $this->getJobDocumentRequirement(),
                'pengalaman' => rand(0, 1),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Keahlian Lowongan 
            $keahlianLowongan = [
                [
                    'lowongan_id' => $lowonganId,
                    'keahlian_id' => rand(1, 5),
                    'kemampuan_minimum' => 'menengah',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'lowongan_id' => $lowonganId,
                    'keahlian_id' => rand(1, 5),
                    'kemampuan_minimum' => 'pemula',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];
            DB::table('keahlian_lowongan')->insert($keahlianLowongan);
        }
    }
}
