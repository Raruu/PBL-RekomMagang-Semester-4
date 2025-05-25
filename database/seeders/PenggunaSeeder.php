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

        // Dosen JTI yang ber-NIP doang
        $dosen = [
            ['Prof. Dr. Eng. Rosa Andrie Asmara, S.T., M.T.', '198010102005011001'],
            ['Pramana Yoga Saputra, S.Kom., M.MT.', '198805042015041001'],
            ['Luqman Affandi, S.Kom., M.MSI.', '198211302014041001'],
            ['Gunawan Budiprasetyo, S.T., M.MT., Ph.D.', '197704242008121001'],
            ['Hendra Pradibta, S.E., M.Sc.', '198305212006041003'],
            ['Dr. Ely Setyo Astuti, S.T., M.T.', '197605152009122001'],
            ['Mungki Astiningrum, S.T., M.Kom.', '197710302005012001'],
            ['Ade Ismail, S.Kom., M.TI.', '199107042019031021'],
            ['Adevian Fairuz Pratama, S.ST, M.Eng.', '200482071'],
            ['Agung Nugroho Pramudhita, S.T., M.T.', '198902102019031020'],
            ['Ahmad Baha\'uddin Almu\'faro, S.Pd.I., M.Pd.I.', '170361025'],
            ['Ahmadi Yuli Ananta, S.T., M.M.', '198107052005011002'],
            ['Annisa Puspa Kirana, S.Kom, M.Kom.', '198901232019032016'],
            ['Annisa Taufika Firdausi, S.T., M.T.', '198712142019032012'],
            ['Anugrah Nur Rahmanto, S.Sn, M.Ds.', '199112302019031016'],
            ['Ariadi Retno Tri Hayati Ririd, S.Kom., M.Kom.', '198108102005012002'],
            ['Arie Rachmad Syulistyo, S.Kom., M.Kom.', '198708242019031010'],
            ['Arief Prasetyo, S.Kom., M.Kom.', '197903132008121002'],
            ['Dr. Ir. Arwin Datumaya Wahyudi Sumari, S.T., M.T.IPM.ASEANEng.', '190881249'],
            ['Astrifidha Rahma Amalia, S.Pd., M.Pd.', '199405212022032000'],
            ['Atiqah Nurul Asri, S.Pd., M.Pd.', '197606252005012001'],
            ['Bagas Satya Dian Nugraha, S.T, M.T.', '199006192019031017'],
            ['Dr. Eng. Banni Satria Andoko, S.Kom., M.MSI.', '198108092010121002'],
            ['Budi Harijanto, S.T., M.MKom.', '196201051990031002'],
            ['Prof. Cahya Rahmad, S.T., M.Kom., Dr. Eng.', '197202022005011002'],
            ['Candra Bella Vista, S.Kom, M.T.', '199412172019032020'],
            ['Ir. Deddy Kusbianto Purwoko Aji, M.MKom.', '196211281988111001'],
            ['Dhebys Suryani Hormansyah, S.Kom., M.T.', '198311092014042001'],
            ['Dian Hanifudin Subhi, S.Kom., M.Kom.', '198806102019031018'],
            ['Dika Rizky Yunianto, S.Kom., M.Kom.', '199206062019031017'],
            ['Dimas Wahyu Wibowo, S.T., M.T.', '198410092015041001'],
            ['Eka Larasati Amalia, S.ST., M.T.', '198807112015042005'],
            ['Ekojono, S.T., M.Kom.', '195912081985031004'],
            ['Elok Nur Hamdana, S.T., M.T.', '198610022019032011'],
            ['Endah Septa Sintiya, S.Pd., M.Kom.', '199401312022032007'],
            ['Erfan Rohadi, S.T., M.Eng., Ph.D.', '197201232008011006'],
            ['Faiz Ushbah Mubarok, S.Pd, M.Pd.', '199305052019031018'],
            ['Farid Angga Pribadi, S.Kom., M.Kom.', '198910072020121003'],
            ['Farida Ulfa, S.Pd., M.Pd.', '198004142023212020'],
            ['Habibie Ed Dien, S.Kom., M.T.', '199204122019031013'],
            ['Dra. Henny Purwaningsih, M.Pd.', '195911101986032000'],
            ['Ika Kusumaning Putri, S.Kom., M.T.', '199110142019032020'],
            ['Imam Fahrur Rozi, S.T., M.T.', '198406102008121004'],
            ['Dr. Indra Dharma Wijaya, S.T., M.MT.', '197305102008011010'],
            ['Irsyad Arif Mashudi, S.Kom., M.Kom.', '198902012019031009'],
            ['Kadek Suarjuna Batubulan, S.Kom., M.T.', '199003202019031016'],
            ['M. Hasyim Ratsanjani, S.Kom., M.Kom.', '199003052019031013'],
            ['Mamluatul Hani\'ah, S.Kom., M.Kom.', '199002062019032013'],
            ['Meyti Eka Apriyani, S.T., M.T.', '198704242019032017'],
            ['Milyun Nima Shoumi, S.Kom., M.Kom.', '198805072019032012'],
            ['Moch. Zawaruddin Abdullah, S.ST., M.Kom.', '198902102019031019'],
            ['Muhammad Afif Hendrawan, S.Kom., M.T.', '199111282019031013'],
            ['Muhammad Shulhan Khairy, S.Kom., M.Kom.', '199205172019031020'],
            ['Muhammad Unggul Pamenang, S.ST., M.T.', '180461018'],
            ['Mustika Mentari, S.Kom., M.Kom.', '198806072019032016'],
            ['Noprianto, S.Kom., M.Eng.', '198911082019031020'],
            ['Putra Prima Arhandi, S.T., M.Kom.', '198611032014041001'],
            ['Dr. Rakhmat Arianto, S.ST., M.Kom.', '198701082019031004'],
            ['Drs. Rawansyah, M.Pd', '195906201994031001'],
            ['Retno Damayanti, S.Pd., M.T.', '198910042019032023'],
            ['Ridwan Rismanto, S.ST., M.Kom.', '198603182012121001'],
            ['Rizki Putri Ramadhani, S.S., M.Pd.', '199004102019092001'],
            ['Robby Anggriawan, S.E, M.E.', '190861226'],
            ['Rokhimatul Wakhidah, S.Pd., M.T.', '198903192019032013'],
            ['Rudy Ariyanto, S.T., M.Cs.', '197111101999031002'],
            ['Satrio Binusa Suryadi, S.S., M.Pd.', '198910262023211020'],
            ['Septian Enggar Sukmana, S.Pd., M.T.', '198909012019031010'],
            ['Dr. Shohib Muslim, S.H., M.Hum.', '198507222014041001'],
            ['Sofyan Noor Arief, S.ST., M.Kom.', '198908132019031017'],
            ['Triana Fatmawati, S.T., M.T.', '198005142005022001'],
            ['Dr. Ulla Delfana Rosiani, S.T., M.T.', '197803272003122002'],
            ['Usman Nurhasan, S.Kom., M.T.', '198609232015041001'],
            ['Vipkas Al Hadid Firdaus, S.T, M.T.', '199105052019031029'],
            ['Vit Zuraida, S.Kom., M.Kom.', '198901092020122005'],
            ['Vivi Nur Wijayaningrum, S.Kom., M.Kom.', '199308112019032025'],
            ['Vivin Ayu Lestari, S.Pd., M.Kom.', '199106212019032020'],
            ['Dr. Widaningsih condrowardhani, S.Psi., S.H., M.H.', '198103182010122002'],
            ['Wilda Imama Sabilla, S.Kom., M.Kom.', '199208292019032023'],
            ['Yan Watequlis Syaifudin, S.T., M.MT., Ph.D.', '198101052005011005'],
            ['Yoppy Yunhasnawa, S.ST., M.Sc.', '198906212019031013'],
            ['Yuri Ariyanto, S.Kom., M.Kom.', '198007162010121002'],
            ['Zulmy Faqihuddin Putera, S.Pd., M.Pd.', '199005112019091000'],
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
            ['ACHMAD MAULANA HAMZAH', '2341720172'],
            ['ALVANZA SAPUTRA YUDHA', '2341720182'],
            ['ANYA CALUSSTA CHRISWANTARI', '2341720234'],
            ['BERYL FUNKY MUBAROK', '2341720256'],
            ['CANDRA AHMAD DANI', '2341720187'],
            ['CINDY LAILI LARASATI', '2341720038'],
            ['DIKA ARIE ARRIFEY', '2341720232'],
            ['FAHMI YAHYA', '2341720089'],
            ['GILANG PURNOMO', '2341720042'],
            ['GWIDO PUTRA WIJAYA', '2341720103'],
            ['HIDAYAT WIDI SAPUTRA', '2341720157'],
            ['ILHAM FATURACHMAN', '2341720411'], // Fixed from scientific notation
            ['INNAMA MAESA PUTRI', '2341720235'],
            ['JIHA RAMDHAN', '2341720043'],
            ['LEIVTA MEYDA AVU BUDIYANTI', '2341720124'],
            ['M. FATIH AL GHIFARY', '2341720194'],
            ['M. FIRMANSYAH', '2341720099'],
            ['MOCH. ALFIN BURHANUDIN ALQODRI', '2341720024'],
            ['MUHAMAD SYAIFULIAH', '2341720013'],
            ['MUHAMMAD NUR AZIZ', '2341720237'],
            ['NAUWA ALYA NURIZZAH', '2341720230'],
            ['NECHA SYIFA SYAFITRI', '2341720167'],
            ['NOKELENT FARDIAN ERIX', '2341720082'],
            ['OCTRIAN ADILUHUNG TITO PUTRA', '2341720078'],
            ['SATRIO AHMAD RAMADHANI', '2341720163'],
            ['SESY TANA LINA RAHMATIN', '2341720029'],
            ['TAUFIK DIMAS EDYSTARA', '2341720062'],
            ['VINCENTIUS LEONANDA PRABOWO', '2341720149'],
            ['YANUAR RIZKI AMINUDIN', '2341720030'],
            ['Andi Wijaya', '2341721001'],
            ['Budi Santoso', '2341721002'],
            ['Citra Dewi', '2341721003'],
            ['Dian Pratama', '2341721004'],
            ['Eka Putri', '2341721005'],
            ['Fajar Nugroho', '2341721006'],
            ['Gita Sari', '2341721007'],
            ['Hadi Susanto', '2341721008'],
            ['Indah Permata', '2341721009'],
            ['Joko Saputra', '2341721010'],
            ['Kartika Wulandari', '2341721011'],
            ['Luki Hermawan', '2341721012'],
            ['Maya Indah', '2341721013'],
            ['Nur Hasanah', '2341721014'],
            ['Oki Setiawan', '2341721015'],
            ['Putri Ayu', '2341721016'],
            ['Rudi Hartono', '2341721017'],
            ['Siti Rahayu', '2341721018'],
            ['Tono Wibowo', '2341721019'],
            ['Wulan Sari', '2341721020'],
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
                'nama' => $mhs[0],
                'nim' =>  $mhs[1],
                'program_id' => $index > 29 ? rand(1, 3) : 1,
                'semester' => rand(3, 8),
                'nomor_telepon' => '0813' . str_pad($index + 1000000, 7, '0'),
                'foto_profil' => '',
                'file_cv' => '',
                'ipk' => min(4.00, rand(2, 4) + (rand(0, 99) / 100)),
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
