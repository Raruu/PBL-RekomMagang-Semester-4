<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    protected function getRandomAddress()
    {
        $streets = ['Jl. Merdeka', 'Jl. Sudirman', 'Jl. Thamrin', 'Jl. Gatot Subroto', 'Jl. Hayam Wuruk'];
        $cities = ['Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Yogyakarta'];
        return $streets[array_rand($streets)] . ' No. ' . mt_rand(1, 100) . ', ' . $cities[array_rand($cities)];
    }

    protected function getRandomResearchInterests()
    {
        $interests = [
            'Artificial Intelligence, Machine Learning',
            'Computer Networks, Cybersecurity',
            'Database Systems, Big Data',
            'Human-Computer Interaction, UX Design',
            'Software Engineering, Agile Development'
        ];
        return $interests[array_rand($interests)];
    }

    protected function getRandomIndustries()
    {
        $industries = [
            'Teknologi Informasi',
            'E-commerce',
            'Perbankan',
            'Telekomunikasi',
            'Startup Digital'
        ];
        $selectedIndustries = array_rand(array_flip($industries), mt_rand(1, 3));
        return is_array($selectedIndustries) ? implode(', ', $selectedIndustries) : $selectedIndustries;
    }

    protected function getRandomPositions()
    {
        $positions = [
            'Software Developer',
            'Data Analyst',
            'UI/UX Designer',
            'Network Engineer',
            'Database Administrator'
        ];
        $selectedPositions = array_rand(array_flip($positions), mt_rand(1, 2));
        return is_array($selectedPositions) ? implode(', ', $selectedPositions) : $selectedPositions;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        $adminId = DB::table('user')->insertGetId([
            'username' => 'admin',
            'password' => Hash::make('12345'),
            'email' => 'admin@magangsystem.ac.id',
            'role' => 'admin',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('profil_admin')->insert([
            'admin_id' => $adminId,
            'nama' => 'Dr. Bambang Sutrisno, M.Kom.',
            'nomor_telepon' => '081234567890',
            'foto_profil' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Dosen
        $dosen = [
            ['Prof. Dr. Siti Aminah, M.Sc.', '198010001'],
            ['Dr. Ahmad Fauzi, M.T.',  '198010002'],
            ['Dr. Rina Dewi, M.Kom.', '198010003'],
            ['Dr. Eko Prasetyo, M.Eng.',  '198010004'],
            ['Dr. Linda Wati, M.Sc.',  '198010005'],
        ];

        foreach ($dosen as $index => $dsn) {
            $dosenId = DB::table('user')->insertGetId([
                'username' => $dsn[1],
                'password' => Hash::make('12345'),
                'email' => str_replace(' ', '', strtolower($dsn[0])) . '@magangsystem.ac.id',
                'role' => 'dosen',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('profil_dosen')->insert([
                'dosen_id' => $dosenId,
                'lokasi_id' => 1,
                'nama' => $dsn[0],
                'nip' => $dsn[1],
                'program_id' => 1,
                'minat_penelitian' => $this->getRandomResearchInterests(),
                'nomor_telepon' => '0812' . str_pad($index + 1000000, 7, '0'),
                'foto_profil' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Mahasiswa
        $mahasiswa = [
            'Andi Wijaya',
            'Budi Santoso',
            'Citra Dewi',
            'Dian Pratama',
            'Eka Putri',
            'Fajar Nugroho',
            'Gita Sari',
            'Hadi Susanto',
            'Indah Permata',
            'Joko Saputra',
            'Kartika Wulandari',
            'Luki Hermawan',
            'Maya Indah',
            'Nur Hasanah',
            'Oki Setiawan',
            'Putri Ayu',
            'Rudi Hartono',
            'Siti Rahayu',
            'Tono Wibowo',
            'Wulan Sari'
        ];

        foreach ($mahasiswa as $index => $mhs) {
            $nim = '234172' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);

            $mhsId = DB::table('user')->insertGetId([
                'username' => $nim,
                'password' => Hash::make('12345'),
                'email' => str_replace(' ', '', strtolower($mhs)) . '@student.magangsystem.ac.id',
                'role' => 'mahasiswa',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('profil_mahasiswa')->insert([
                'mahasiswa_id' => $mhsId,
                'lokasi_id' => 1,
                'nama' => $mhs,
                'nim' =>  $nim,
                'program_id' => 1,
                'semester' => rand(3, 8),
                'nomor_telepon' => '0813' . str_pad($index + 1000000, 7, '0'),
                'alamat' => $this->getRandomAddress(),
                'foto_profil' => '',
                'file_cv' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Preferensi Mahasiswa
            DB::table('preferensi_mahasiswa')->insert([
                'mahasiswa_id' => $mhsId,
                'lokasi_id' => rand(1, 3),
                'industri_preferensi' => $this->getRandomIndustries(),
                'posisi_preferensi' => $this->getRandomPositions(),
                'tipe_kerja_preferensi' => ['onsite', 'remote', 'hybrid', 'semua'][rand(0, 3)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Keahlian Mahasiswa
            $jumlahKeahlian = rand(3, 6);
            $keahlianTerpilih = array_rand(range(1, 10), $jumlahKeahlian);

            if (!is_array($keahlianTerpilih)) {
                $keahlianTerpilih = [$keahlianTerpilih];
            }

            foreach ($keahlianTerpilih as $keahlianId) {
                DB::table('keahlian_mahasiswa')->insert([
                    'mahasiswa_id' => $mhsId,
                    'keahlian_id' => $keahlianId + 1,
                    'tingkat_kemampuan' => ['pemula', 'menengah', 'mahir', 'ahli'][rand(0, 3)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
