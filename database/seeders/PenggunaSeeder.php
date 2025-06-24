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
        return 'Jl Lorem Ipsum No. ' . rand(1, 100) . ', Jakarta, Indonesia';
    }

    protected function getRandomResearchInterests()
    {
        $interests = [
            // From first image
            'Mikroprosesor, Mikrokontroler, Komputer',
            'Fluid Phase Equilibria, Ionic Liquids, Zeolites',
            'Electronic Engineering',
            'Teknik Elektro',
            'Electrical Engineering',
            'Control System',
            'Biosensor, Electronic, Semiconductor, Renewable Energy',
            'English Language Teaching',
            'Mektronika dan Renewable Energy',
            'Control Engineering, Embedded System',
            'Instrumentasi Elektronika',
            'Digital & Kontrol System',
            'Digital Signal Processing',
            'Mikroprosesor',
            'Elektronik',

            // From second image
            'Fisika dan Pneumatic Hydraulic',
            'Power System, Industrial Automation',
            'Electrical Power Engineering',
            'Project Management',
            'Power System, Industrial Installation Design',
            'Electrical Installation Design',
            'Electrical Installation Engineering',
            'Power Control, Distributed Generation, Renewable Energy, Energy Storage System',
            'Power Systems, Electrical Installation, Renewable Energy',
            'Electrical Circuits and Electric Machine',
            'Transformer and Electric Machine',
            'Power Electronics',
            'Substation, Thermodynamics',
            'Hybrid PhotoVoltaic, Wind Power System and Renewable Energy',
            'Power Electronics, Renewable Energy',
            'Mathematic',
            'Control System and SCADA',
            'Power System',
            'Administrative Law, Humanities, Sociology',

            // From third image
            'Project Management',
            'PLC dan Mikrokontroler',
            'Power System, Power Plant Engineering',
            'Power Engineering',
            'English',
            'Electrical Circuit and Protection of Power System',
            'Installation and Electric Machine',
            'Tranformator and Electric Machine',
            'Electrical and Renewable Energy',
            'Power Plant',
            'Industrial Electrical Installation Design',
            'Substation, Instrumentation',
            'Power System and High Voltage apparatus',
            'Control and automation system',
            'Condition Monitoring and Diagnostics Electrical Equipments',
            'Automation and Robotics',
            'Power System, Control System',
            'Management',
            'Safety and Health',
            'Electrical Installation Design',
            'Analog and Digital Electronics',


            'Artificial Intelligence, Machine Learning',
            'Computer Networks, Cybersecurity',
            'Database Systems, Big Data',
            'Human-Computer Interaction, UX Design',
            'Software Engineering, Agile Development'
        ];

        return $interests[array_rand($interests)];
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
            'email' => 'admin@test.ac.id',
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

        $adminRaruu = DB::table('user')->insertGetId([
            'username' => 'raruu',
            'password' => Hash::make('123456'),
            'email' => 'raruu@test.ac.id',
            'role' => 'admin',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('profil_admin')->insert([
            'admin_id' => $adminRaruu,
            'nama' => 'Raruu',
            'nomor_telepon' => '081234567890',
            'foto_profil' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $dosen = [
            // Dosen JTI yang ber-NIP doang
            ['Prof. Dr. Eng. Rosa Andrie Asmara, S.T., M.T.', '198010102005011001'],
            ['Pramana Yoga Saputra, S.Kom., M.MT.', '198805042015041001'],
            ['Luqman Affandi, S.Kom., M.MSI.', '198211302014041001'],
            ['Gunawan Budiprasetyo, S.T., M.MT., Ph.D.', '197704242008121001'],
            ['Hendra Pradibta, S.E., M.Sc.', '198305212006041003'],
            ['Dr. Ely Setyo Astuti, S.T., M.T.', '197605152009122001'],
            ['Mungki Astiningrum, S.T., M.Kom.', '197710302005012001'],
            ['Ade Ismail, S.Kom., M.TI.', '199107042019031021'],
           
        ];

        foreach ($dosen as $index => $dsn) {
            $dosenId = DB::table('user')->insertGetId([
                'username' => $dsn[1],
                'password' => Hash::make('12345'),
                'email' => 'dosen' . ($index + 1) . '@test.ac.id',
                'role' => 'dosen',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('profil_dosen')->insert([
                'dosen_id' => $dosenId,
                'lokasi_id' => DB::table('lokasi')->insertGetId([
                    'alamat' => $this->getRandomAddress(),
                    'latitude' => -6.200000 + mt_rand(-1000, 1000) / 10000,
                    'longitude' => 106.816666 + mt_rand(-1000, 1000) / 10000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]),
                'nama' => $dsn[0],
                'nip' => $dsn[1],
                'program_id' =>  $index > 81 ? rand(3, 5) : rand(1, 2),
                'minat_penelitian' => $this->getRandomResearchInterests(),
                'nomor_telepon' => '0812' . str_pad($index + 1000000, 7, '0'),
                'foto_profil' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Mahasiswa
        $mahasiswa = [
            ['Hidayat Widi Saputra', '2341720157'],
            ['testing', '0000000000'], // Jangan Hapus
        ];

        foreach ($mahasiswa as $index => $mhs) {
            $mhsId = DB::table('user')->insertGetId([
                'username' => $mhs[1],
                'password' => Hash::make('12345'),
                'email' => $mhs[1] == '0000000000' ? 'testing@test.ac.id' : 'mahasiswa' . $index . '@test.ac.id',
                'role' => 'mahasiswa',
                'is_active' => $mhs[1] == '0000000000' ? 0 : 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('profil_mahasiswa')->insert([
                'mahasiswa_id' => $mhsId,
                'lokasi_id' => DB::table('lokasi')->insertGetId([
                    'alamat' => $this->getRandomAddress(),
                    'latitude' => -6.200000 + mt_rand(-1000, 1000) / 10000,
                    'longitude' => 106.816666 + mt_rand(-1000, 1000) / 10000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]),
                'nama' => $mhs[0],
                'nim' =>  $mhs[1],
                'program_id' => $index > 28 ? rand(1, 3) : 1,
                'angkatan' => $index > 28 ?  rand(2019, 2024) : 2025,
                'nomor_telepon' => '0813' . str_pad($index + 1000000, 7, '0'),
                'foto_profil' => '',
                'file_cv' => '',
                'file_transkrip_nilai' => '',
                'ipk' => min(4.00, rand(2, 4) + (rand(0, 99) / 100)),
                'verified' => 1,
                'completed_profil' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Preferensi Mahasiswa
            DB::table('preferensi_mahasiswa')->insert([
                'mahasiswa_id' => $mhsId,
                'lokasi_id' =>  DB::table('lokasi')->insertGetId([
                    'alamat' => $this->getRandomAddress(),
                    'latitude' => -6.200000 + mt_rand(-1000, 1000) / 10000,
                    'longitude' => 106.816666 + mt_rand(-1000, 1000) / 10000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]),
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
