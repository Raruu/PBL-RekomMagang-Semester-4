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

            // From first image
            ['Ir. Achmad Komarudin, MMT.', '1958092998702002'],
            ['Drs. Agus Procoyo, MT.', '19590817086030005'],
            ['Ir. Ari Murtono, MT.', '1960030798702001'],
            ['Bambang Priyadi, S.T., MT.', '1958030090031001'],
            ['Beauty Anggreneny Ikawanty, S.T., MT.', '19810312009122001'],
            ['Denda Dewatama, S.T., MT.', '19781227200501001'],
            ['Daddy Maulana, S.E., M.Sc., MT.', '19720418200212001'],
            ['Edi Sulistio Budi, S.T., MT.', '1982022399031001'],
            ['Hairus, S.H., M.H.', '19750708200604002'],
            ['Hari Kurnia Safitri, S.T., MT.', '19730732002122002'],
            ['Herwandi, S.T., MT.', '1982103199003001'],
            ['Indrawan Nugrahanto, S.S.T., MT.', '1988108209031012'],
            ['Leonardo Kamajaya, S.S.T., M.Sc.', '19870806209030100'],
            ['Mariana Ulfah Hoesny, S.S., MPD', '198009182006042002'],
            ['Muhammad Khainuddin, S.S.T., M.Sc.', '19900419201903108'],
            ['Sidik Nureahyo, S.T., MT.', '1977120200501002'],
            ['Drs. Siswoko, M.M.T., M.M.', '19580628198503001'],
            ['Sungkono, S.T., MT.', '19701120102001'],
            ['Ir. Agus Sukoco Heru Sumarno, M.T.', '19630810988031002'],
            ['Dr. Andriani Parastivi, BS.EET, M.T.', '1963029994032001'],
            ['Donny Racianto, S.T., MENG', '197409262003121001'],
            ['Drs. Drs. Eka Mandayatma, M.T.', '19600513098031002'],
            ['Drs. Fathoni, M.T.', '19581181968031002'],
            ['Drs. Herman Hariyadi, M.T.', '19580421958031003'],
            ['Drs. Marcahyono, M.T.', '19560505198603001'],
            ['Mila Fauziyah, S.T., M.T.', '197505042000032001'],
            ['Ir. Mohammad Luqman, MS', '19640228198803004'],
            ['Muhamad Rifai, S.T., M.T.', '19750325200501001'],
            ['Dr. Ratna Ika Putri, S.T., M.T.', '1977022200122001'],
            ['Ir. Subiyantoro, M.T.', '1957005189702001'],
            ['Supriatha Adhisuwigajo, S.T., M.T.', '19710108199030101sup'],
            ['Ir. Totok Winamo, M.T.', '19600101885030102'],
            ['Dr. Ir. Tundung Subati Patma, M.T.', '195904241988031002'],
            ['Ir. Yulianto, M.T.', '19590726198603001'],

            // From second image
            ['Drs. Abdullah Mas\'Ud, M.T.', '19560328B85031001'],
            ['Anang Dasa Novfowan, B.Tech, M.MT.', '19631109B03002'],
            ['Asfari Hariz Santoso, S.T, M.T', '199102120903103'],
            ['Drs. Awan Setiawan, M.M.', '19590910B86031002'],
            ['Chandra Wiharya, S.ST., M.T.', '198510292015041002'],
            ['Drs. Ephwardi, M.MT.', '195906031986031004'],
            ['Ferdian Ronilaya, S.T., M.Sc., Ph.D.', '19790501200501003'],
            ['Ir. Gatot Joelianto, M.MT.', '19550710B86031001'],
            ['Drs. Hari Suchjato, M.M.', '19550420B87031001'],
            ['Dr. Harrij Mukti K', '19741251999032001'],
            ['Hendro Buwono, S.T., M.MT.', '19540107B84031003'],
            ['Heri Sungkowo, S.ST., M.MT.', '196008031985031005'],
            ['Imran Ridzki, S.T., M.T.', '19710518B99031002'],
            ['Masramahani Saputra, S.T., M.T.', '19003282010030104'],
            ['Drs. Mugijono, M.P.d.', '19590121B88031001'],
            ['Popong Effendrik, S.T., M.Sc.', '19740817200501002'],
            ['Rachmat Suijipto, B.Tech., M.MT.', '19631019B91031001'],
            ['Rohmantta Duanoputri, S.ST., M.T.', '1991121209032022'],
            ['Rokiyah, S.H., M.H.', '197407092010122001'],
            ['Ruwah Joto, S.T., M.MT.', '19610125B90031001'],

            // From third image
            ['Sukamdi, S.T., M.MT.', '19600715199003107'],
            ['Drs. Tresna Umar Syamsuri, M.T.', '19591021987031007'],
            ['Ahmad Hermawan, S.T., M.T.', '19660622199512001'],
            ['Drs. Aly Imran, M.Pd.', '196208141993031001'],
            ['Ir. Budi Eko Praseyko, M.MT.', '19600312987021001'],
            ['Dr.Harrij Mukti K, S.T., M.T.', '197412251999032001'],
            ['Ihwan Heryanto/Eryk, S.T., M.T.', '19760512201012002'],
            ['Ir. James Edward Arby, M.T.', '194903091988031001'],
            ['Dr. Khrisna Hadiwinata, S.H., M.H.', '198407072008122002'],
            ['Mochammad Mieftah, S.ST., M.MT.', '196008319900301001'],
            ['Muhammad Fahmi Hakim, S.T., M.T.', '198412292014041001'],
            ['Rahma Nur Amalia, S.T., M.T.', '199009082019032019'],
            ['Rahman Azis Prasojo, S.ST., M.T.', '199405072019031017'],
            ['Rhezal Agung Ananto, S.T., M.T.', '199007032019031014'],
            ['Sapto Wibowo, S.T., M.Sc., Ph.D.', '19760314200312003'],
            ['Sigil Syah Wibowo, B.Tech., M.T.', '196409161991031002'],
            ['Sigil Seiya Witwaha, S.T., M.M.', '197304032002121001'],
            ['Slamet Nurhadi, S.T., M.MT.', '196206301986121001'],
            ['Sulistyowati, S.T., M.T.', '19702321997022001'],
            ['Ir. Wijaya Kusuma, M.MT.', '1962126198811001'],
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
            ['Achmad Maulana Hamzah', '2341720172'],
            ['Alvanza Saputra Yudha', '2341720182'],
            ['Anya Calussta Chriswantari', '2341720234'],
            ['Beryl Funky Mubarok', '2341720256'],
            ['Candra Ahmad Dani', '2341720187'],
            ['Cindy Laili Larasati', '2341720038'],
            ['Dika Arie Arrifei', '2341720232'],
            ['Fahmi Yahya', '2341720089'],
            ['Gilang Purnomo', '2341720042'],
            ['Gwido Putra Wijaya', '2341720103'],
            ['Hidayat Widi Saputra', '2341720157'],
            ['Ilham Faturachman', '2341720411'],
            ['Innama Maesa Putri', '2341720235'],
            ['Jiha Ramdhan', '2341720043'],
            ['Leivta Meyda Avu Budiyyanti', '2341720124'],
            ['M. Fatih Al Ghifary', '2341720194'],
            ['M. Firmansyah', '2341720099'],
            ['Moch. Alfin Burhanudin Alqodri', '2341720024'],
            ['Muhamad Syaifuliah', '2341720013'],
            ['Muhammad Nur Aziz', '2341720237'],
            ['Nauwa Alya Nurizzah', '2341720230'],
            ['Nokurento Fardian Erix', '2341720082'],
            ['Octrian Adiluhung Tito Putra', '2341720078'],
            ['Satrio Ahmad Ramadhani', '2341720163'],
            ['Sesya Tana Lina Rahmatin', '2341720029'],
            ['Taupik Dimas Edystara', '2341720062'],
            ['Vincentius Leonanda Prabowo', '2341720149'],
            ['Yanuar Rizki Aminudin', '2341720030'],
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

            // Keahlian Mahasiswa (exclude SQL Server, Unity, MongoDB, Java)
            $excludedSkills = ['SQL Server', 'Unity', 'MongoDB', 'Java'];

            // Get allowed keahlian IDs by excluding the specified skill names
            $allowedKeahlianIds = DB::table('keahlian')
                ->whereNotIn('nama_keahlian', $excludedSkills)
                ->pluck('keahlian_id')
                ->toArray();

            // Fallback: if exclusion returns empty (e.g., skills not found), use all IDs
            if (empty($allowedKeahlianIds)) {
                $allowedKeahlianIds = DB::table('keahlian')->pluck('keahlian_id')->toArray();
            }

            $jumlahKeahlian = rand(3, 6);
            $jumlahKeahlian = min($jumlahKeahlian, count($allowedKeahlianIds));

            // Randomly pick unique IDs from the allowed list
            shuffle($allowedKeahlianIds);
            $keahlianTerpilih = array_slice($allowedKeahlianIds, 0, $jumlahKeahlian);

            foreach ($keahlianTerpilih as $keahlianId) {
                DB::table('keahlian_mahasiswa')->insert([
                    'mahasiswa_id' => $mhsId,
                    'keahlian_id' => $keahlianId,
                    'tingkat_kemampuan' => ['pemula', 'menengah', 'mahir', 'ahli'][rand(0, 3)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
