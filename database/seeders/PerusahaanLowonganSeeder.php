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
            'Mahasiswa aktif',
            'Memiliki pengetahuan dasar di bidang terkait',
            'Bersedia belajar teknologi baru',
            'Dapat bekerja dalam tim',
            'Memiliki motivasi tinggi',
            'Mampu berkomunikasi dengan baik',
            'Memiliki portofolio/referensi terkait',
            'Bersedia bekerja ' . (rand(0, 1) ? 'full-time' : 'part-time') . ' selama magang'
        ];
        return implode("; ", array_intersect_key($requirements, array_flip(array_rand($requirements, rand(3, 5)))));
    }

    protected function getJobDocumentRequirement()
    {
        if (rand(0, 3) == 0) { // 25% chance no documents required
            return null;
        }

        $documents = [
            'KTP',
            'Surat Keterangan Mahasiswa Aktif',
            'Transkrip Nilai',            
            'Portofolio',
            'Surat Rekomendasi',
            'Ijazah atau SKL',
            'Pas Foto',
            'Surat Keterangan Sehat'
        ];

        shuffle($documents);
        return implode(';', array_slice($documents, 0, rand(1, 4)));
    }

    protected function generateCompanyName($industry)
    {
        $prefixes = ['PT', 'CV', 'UD', 'PD'];
        $buzzwords = [
            'Teknologi' => ['Digital', 'Inovasi', 'Solusi', 'Kreasi', 'Maju'],
            'IT Konsultan' => ['Solusi', 'Sistem', 'Jaringan', 'Data', 'Integrasi'],
            'Desain' => ['Kreasi', 'Visual', 'Grafis', 'Warna', 'Seni'],
            'Big Data' => ['Analitik', 'Data', 'Insight', 'Prediktif', 'Kuantum'],
            'Telekomunikasi' => ['Jaringan', 'Koneksi', 'Satelit', 'Broadband', 'Nirkabel']
        ];

        return $prefixes[array_rand($prefixes)] . ". " .
            $buzzwords[$industry][array_rand($buzzwords[$industry])] . " " .
            ['Indonesia', 'Nusantara', 'Global', 'Tekno', 'Sukses'][array_rand([0, 1, 2, 3, 4])];
    }

    protected function generateCompanyWebsite($name)
    {
        $cleaned = strtolower(str_replace([' ', '.', 'PT', 'CV', 'UD', 'PD'], '', $name));
        return "https://www.$cleaned" . ['.com', '.id', '.co.id', '.net'][array_rand([0, 1, 2, 3])];
    }

    protected function generateCompanyLocation($city)
    {
        $coords = [
            'Jakarta' => ['lat' => -6.2, 'lng' => 106.816666],
            'Bandung' => ['lat' => -6.902, 'lng' => 107.618333],
            'Surabaya' => ['lat' => -7.274, 'lng' => 112.7375],
            'Yogyakarta' => ['lat' => -7.7956, 'lng' => 110.3695],
            'Bali' => ['lat' => -8.3405, 'lng' => 115.0920]
        ];

        $variation = rand(-1000, 1000) / 10000;
        return [
            'alamat' => 'Jl. ' . ['Pendidikan', 'Industri', 'Teknologi', 'Raya', 'Profesional'][array_rand([0, 1, 2, 3, 4])] .
                ' No. ' . rand(1, 100) . ', ' . $city,
            'latitude' => $coords[$city]['lat'] + $variation,
            'longitude' => $coords[$city]['lng'] + $variation,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    protected function generatePhoneNumber($areaCode)
    {
        return $areaCode . substr(str_shuffle('1234567890'), 0, 7);
    }

    protected function generateJobTitle($position)
    {
        $formats = [
            'Magang {position}',
            'Program Magang {position}',
            'Kesempatan Magang {position}',
            'Internship {position}',
            'Lowongan Magang {position}'
        ];

        $positionNames = [
            'Frontend Developer' => ['Frontend Developer', 'Pengembang Frontend', 'Frontend Engineer'],
            'UI/UX Designer' => ['UI/UX Designer', 'Desainer UI/UX', 'Spesialis UX'],
            'Data Analyst' => ['Data Analyst', 'Analis Data', 'Data Specialist'],
            'Graphic Designer' => ['Graphic Designer', 'Desainer Grafis', 'Desainer Visual'],
            'Data Scientist' => ['Data Scientist', 'Spesialis Data Science'],
            'Network Engineer' => ['Network Engineer', 'Teknisi Jaringan'],
            'Software Engineer' => ['Software Engineer', 'Pengembang Perangkat Lunak'],
            'Backend Developer' => ['Backend Developer', 'Pengembang Backend', 'Backend Engineer']
        ];

        $format = $formats[array_rand($formats)];
        $positionName = $positionNames[$position][array_rand($positionNames[$position])];

        return str_replace('{position}', $positionName, $format);
    }

    //  protected function generateJobDescription($position)
    // {
    //     $descriptions = [
    //         'Frontend Developer' => [
    //             "Kembangkan antarmuka pengguna yang menarik dan responsif menggunakan teknologi terbaru.",
    //             "Bergabung dengan tim frontend kami untuk membangun pengalaman pengguna yang memukau.",
    //             "Pelajari dan terapkan framework frontend modern dalam proyek nyata."
    //         ],
    //         'UI/UX Designer' => [
    //             "Bantu kami menciptakan desain antarmuka yang intuitif dan menyenangkan bagi pengguna.",
    //             "Kolaborasikan dengan tim produk untuk merancang solusi visual yang efektif.",
    //             "Pelajari proses desain end-to-end dari research hingga implementasi."
    //         ],
    //         'Data Analyst' => [
    //             "Analisis data untuk menghasilkan insight bisnis yang berharga.",
    //             "Bekerja dengan dataset nyata untuk membantu pengambilan keputusan perusahaan.",
    //             "Pelajari tools analisis data terkini sambil berkontribusi pada proyek nyata."
    //         ],
    //         'Graphic Designer' => [
    //             "Bantu tim kreatif kami menghasilkan desain visual yang menarik.",
    //             "Kerjakan berbagai proyek desain mulai dari digital hingga print media.",
    //             "Kembangkan skill desainmu dengan bimbingan profesional industri."
    //         ],
    //         'Data Scientist' => [
    //             "Bangun model machine learning untuk menyelesaikan masalah bisnis nyata.",
    //             "Bekerja dengan big data dan algoritma prediktif.",
    //             "Pelajari seluruh pipeline data science dari preprocessing hingga deployment."
    //         ],
    //         'Network Engineer' => [
    //             "Pelajari infrastruktur jaringan perusahaan skala menengah-besar.",
    //             "Bantu tim IT memelihara dan mengembangkan sistem jaringan.",
    //             "Dapatkan pengalaman hands-on dengan perangkat jaringan profesional."
    //         ],
    //         'Software Engineer' => [
    //             "Kembangkan solusi perangkat lunak end-to-end dengan tim engineering.",
    //             "Pelajari best practices pengembangan software profesional.",
    //             "Berkontribusi pada siklus pengembangan perangkat lunak lengkap."
    //         ],
    //         'Backend Developer' => [
    //             "Bangun API dan layanan backend yang skalabel.",
    //             "Optimalkan performa sistem dan query database.",
    //             "Pelajari arsitektur backend modern dan pola-pola desain."
    //         ]
    //     ];

    //     return $descriptions[$position][array_rand($descriptions[$position])] . " " .
    //         "Magang ini cocok untuk mahasiswa yang ingin mendapatkan pengalaman praktis di industri.";
    // }

    // protected function generateSalary($position)
    // {
    //     $baseSalaries = [
    //         'Frontend Developer' => 2000000,
    //         'UI/UX Designer' => 1800000,
    //         'Data Analyst' => 2200000,
    //         'Graphic Designer' => 1500000,
    //         'Data Scientist' => 3000000,
    //         'Network Engineer' => 2400000,
    //         'Software Engineer' => 2600000,
    //         'Backend Developer' => 2500000
    //     ];

    //     return $baseSalaries[$position] * (1 + (rand(-10, 10) / 100)); // ±10% variation
    // }

    protected function generateJobDescription($position, $chance)
    {
        $descriptions = [
            'Frontend Developer' => [
                "Kembangkan antarmuka pengguna yang menarik dan responsif menggunakan teknologi terbaru.",
                "Bergabung dengan tim frontend kami untuk membangun pengalaman pengguna yang memukau.",
                "Pelajari dan terapkan framework frontend modern dalam proyek nyata."
            ],
            'UI/UX Designer' => [
                "Bantu kami menciptakan desain antarmuka yang intuitif dan menyenangkan bagi pengguna.",
                "Kolaborasikan dengan tim produk untuk merancang solusi visual yang efektif.",
                "Pelajari proses desain end-to-end dari research hingga implementasi."
            ],
            'Data Analyst' => [
                "Analisis data untuk menghasilkan insight bisnis yang berharga.",
                "Bekerja dengan dataset nyata untuk membantu pengambilan keputusan perusahaan.",
                "Pelajari tools analisis data terkini sambil berkontribusi pada proyek nyata."
            ],
            'Graphic Designer' => [
                "Bantu tim kreatif kami menghasilkan desain visual yang menarik.",
                "Kerjakan berbagai proyek desain mulai dari digital hingga print media.",
                "Kembangkan skill desainmu dengan bimbingan profesional industri."
            ],
            'Data Scientist' => [
                "Bangun model machine learning untuk menyelesaikan masalah bisnis nyata.",
                "Bekerja dengan big data dan algoritma prediktif.",
                "Pelajari seluruh pipeline data science dari preprocessing hingga deployment."
            ],
            'Network Engineer' => [
                "Pelajari infrastruktur jaringan perusahaan skala menengah-besar.",
                "Bantu tim IT memelihara dan mengembangkan sistem jaringan.",
                "Dapatkan pengalaman hands-on dengan perangkat jaringan profesional."
            ],
            'Software Engineer' => [
                "Kembangkan solusi perangkat lunak end-to-end dengan tim engineering.",
                "Pelajari best practices pengembangan software profesional.",
                "Berkontribusi pada siklus pengembangan perangkat lunak lengkap."
            ],
            'Backend Developer' => [
                "Bangun API dan layanan backend yang skalabel.",
                "Optimalkan performa sistem dan query database.",
                "Pelajari arsitektur backend modern dan pola-pola desain."
            ]
        ];

        $baseDescription = $descriptions[$position][array_rand($descriptions[$position])] . " " .
            "Magang ini cocok untuk mahasiswa yang ingin mendapatkan pengalaman praktis di industri.";

        // 30% chance to have no salary but offer incentives
        if ($chance) {
            $incentives = [
                "Tunjangan transportasi",
                "Makan siang gratis",
                "Akses ke pelatihan premium",
                "Sertifikat magang",
                "Kesempatan dipekerjakan setelah lulus",
                "Networking dengan profesional",
                "Fleksibilitas jam kerja",
                "Bonus performa"
            ];

            $selectedIncentives = array_rand($incentives, rand(1, 3));
            if (is_array($selectedIncentives)) {
                $incentiveText = "Keuntungan: \n" .
                    implode(", ", array_map(function ($i) use ($incentives) {
                        return $incentives[$i];
                    }, $selectedIncentives)) . ".";
            } else {
                $incentiveText = "Keuntungan: \n" .
                    $incentives[$selectedIncentives] . ".";
            }

            return $baseDescription . "\n\n" . $incentiveText;
        }

        return $baseDescription;
    }

    protected function generateSalary($position, $chance)
    {
        // 30% chance to return null (no salary)
        if ($chance) {
            return null;
        }

        $baseSalaries = [
            'Frontend Developer' => 2000000,
            'UI/UX Designer' => 1800000,
            'Data Analyst' => 2200000,
            'Graphic Designer' => 1500000,
            'Data Scientist' => 3000000,
            'Network Engineer' => 2400000,
            'Software Engineer' => 2600000,
            'Backend Developer' => 2500000
        ];

        return $baseSalaries[$position] * (1 + (rand(-10, 10) / 100)); // ±10% variation
    }

    protected function generateDateRange()
    {
        $startMonth = rand(1, 6); // January to June
        $startDate = date('Y-m-d', mktime(0, 0, 0, $startMonth, rand(1, 28), 2026));

        $duration = rand(3, 6); // 3-6 months
        $endDate = date('Y-m-d', strtotime("$startDate +$duration months"));

        $deadline = date('Y-m-d', strtotime("$startDate -" . rand(2, 4) . " weeks"));

        return [$startDate, $endDate, $deadline];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed industries
        $industries = [
            ['nama' => 'Teknologi', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'IT Konsultan', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Desain', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Big Data', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Telekomunikasi', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($industries as $industry) {
            DB::table('bidang_industri')->updateOrInsert(
                ['nama' => $industry['nama']],
                $industry
            );
        }

        // Generate companies
        $cities = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Bali'];
        $areaCodes = ['021', '022', '031', '0274', '0361'];
        $companies = [];

        foreach ($industries as $index => $industry) {
            $city = $cities[$index % count($cities)];
            $location = $this->generateCompanyLocation($city);

            $companies[] = [
                'lokasi_id' => DB::table('lokasi')->insertGetId($location),
                'nama_perusahaan' => $this->generateCompanyName($industry['nama']),
                'bidang_id' => DB::table('bidang_industri')->where('nama', $industry['nama'])->value('bidang_id'),
                'website' => $this->generateCompanyWebsite($this->generateCompanyName($industry['nama'])),
                'kontak_email' => 'hrd@' . strtolower(str_replace([' ', '.', 'PT', 'CV', 'UD', 'PD'], '', $this->generateCompanyName($industry['nama']))) . '.com',
                'kontak_telepon' => $this->generatePhoneNumber($areaCodes[$index % count($areaCodes)]),
                'created_at' => now(),
                'updated_at' => now(),
                'is_active' => 1
            ];
        }

        // Insert companies and get their IDs
        $companyIds = [];
        foreach ($companies as $company) {
            $companyIds[] = DB::table('perusahaan')->insertGetId($company);
        }

        // Generate internships
        $positions = [
            'Frontend Developer' => [
                'skills' => ['Pemrograman Web', 'JavaScript', 'React', 'Vue.js', 'HTML/CSS'],
                'category' => 1 // Pemrograman
            ],
            'UI/UX Designer' => [
                'skills' => ['UI/UX Design', 'Desain Grafis', 'Adobe Photoshop', 'Adobe Illustrator', 'Figma'],
                'category' => 2 // Desain
            ],
            'Data Analyst' => [
                'skills' => ['Data Visualization', 'Tableau', 'Python', 'SQL', 'Excel'],
                'category' => 7 // Analisis Data
            ],
            'Graphic Designer' => [
                'skills' => ['Desain Grafis', 'Adobe Photoshop', 'Adobe Illustrator', 'CorelDRAW', 'Typography'],
                'category' => 2 // Desain
            ],
            'Data Scientist' => [
                'skills' => ['Machine Learning', 'Python', 'Data Analysis', 'SQL', 'Statistics'],
                'category' => 5 // Kecerdasan Buatan
            ],
            'Network Engineer' => [
                'skills' => ['Administrasi Jaringan', 'Keamanan Jaringan', 'Cisco Networking', 'TCP/IP', 'Firewall'],
                'category' => 3 // Jaringan
            ],
            'Software Engineer' => [
                'skills' => ['Java', 'Python', 'Software Architecture', 'Git', 'Debugging'],
                'category' => 1 // Pemrograman
            ],
            'Backend Developer' => [
                'skills' => ['Python', 'Java', 'PHP', 'MySQL', 'API Development'],
                'category' => 1 // Pemrograman
            ]
        ];

        $internships = [];
        for ($i = 0; $i < 15; $i++) {
            $companyId = $companyIds[array_rand($companyIds)];
            $position = array_rand($positions);
            $positionData = $positions[$position];
            [$startDate, $endDate, $deadline] = $this->generateDateRange();

            $chance = rand(1, 100) <= 30;

            $internships[] = [
                'perusahaan_id' => $companyId,
                'lokasi_id' => DB::table('lokasi')->insertGetId($this->generateCompanyLocation($cities[array_rand($cities)])),
                'judul_lowongan' => $this->generateJobTitle($position),
                'judul_posisi' => $position,
                'deskripsi' => $this->generateJobDescription($position, $chance),
                'gaji' => $this->generateSalary($position, $chance),
                'kuota' => rand(1, 5),
                'tipe_kerja_lowongan' => ['onsite', 'hybrid', 'remote'][array_rand([0, 1, 2])],
                'tanggal_mulai' => $startDate,
                'tanggal_selesai' => $endDate,
                'batas_pendaftaran' => $deadline,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Insert internships and their requirements
        foreach ($internships as $internship) {
            $internshipId = DB::table('lowongan_magang')->insertGetId($internship);

            // Requirements
            DB::table('persyaratan_magang')->insert([
                'lowongan_id' => $internshipId,
                'minimum_ipk' => rand(25, 38) / 10, // 2.5 - 3.8
                'deskripsi_persyaratan' => $this->getJobRequirementDesc(),
                'dokumen_persyaratan' => $this->getJobDocumentRequirement(),
                'pengalaman' => rand(0, 1),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Get position data
            $position = $internship['judul_posisi'];
            $positionData = $positions[$position];

            // Get relevant skills from database
            $relevantSkills = DB::table('keahlian')
                ->whereIn('nama_keahlian', $positionData['skills'])
                ->orWhere('kategori_id', $positionData['category'])
                ->inRandomOrder()
                ->limit(rand(2, 4))
                ->get();

            // Add skills to internship
            $skills = [];
            $skillLevels = ['pemula', 'menengah', 'mahir'];

            foreach ($relevantSkills as $skill) {
                $skills[] = [
                    'lowongan_id' => $internshipId,
                    'keahlian_id' => $skill->keahlian_id,
                    'kemampuan_minimum' => $skillLevels[array_rand($skillLevels)],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            DB::table('keahlian_lowongan')->insert($skills);
        }

        // Add 2 static test internships for deterministic testing
        $staticCompanyId = $companyIds[0] ?? null;
        if ($staticCompanyId) {
            $staticInternships = [
                [
                    'perusahaan_id' => $staticCompanyId,
                    'lokasi_id' => DB::table('lokasi')->insertGetId($this->generateCompanyLocation('Jakarta')),
                    'judul_lowongan' => 'Magang Frontend Developer (Test) ',
                    'judul_posisi' => 'Frontend Developer',
                    'deskripsi' => "Magang pengujian untuk pengembang frontend.",
                    'gaji' => 1500000,
                    'kuota' => 2,
                    'tipe_kerja_lowongan' => 'onsite',
                    'tanggal_mulai' => '2026-01-15',
                    'tanggal_selesai' => '2026-04-15',
                    'batas_pendaftaran' => '2026-01-01',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'perusahaan_id' => $staticCompanyId,
                    'lokasi_id' => DB::table('lokasi')->insertGetId($this->generateCompanyLocation('Bandung')),
                    'judul_lowongan' => 'Magang Data Analyst (Test) ',
                    'judul_posisi' => 'Data Analyst',
                    'deskripsi' => "Magang pengujian untuk analis data.",
                    'gaji' => 2000000,
                    'kuota' => 1,
                    'tipe_kerja_lowongan' => 'remote',
                    'tanggal_mulai' => '2026-02-01',
                    'tanggal_selesai' => '2026-05-01',
                    'batas_pendaftaran' => '2026-01-20',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];

            foreach ($staticInternships as $static) {
                $id = DB::table('lowongan_magang')->insertGetId($static);

                // Static requirements
                DB::table('persyaratan_magang')->insert([
                    'lowongan_id' => $id,
                    'minimum_ipk' => 3.0,
                    'deskripsi_persyaratan' => 'Mahasiswa aktif, dapat berkomunikasi, dan memiliki dasar pemrograman atau analisis data sesuai posisi.',
                    'dokumen_persyaratan' => 'KTP;Surat Keterangan Mahasiswa Aktif;Transkrip Nilai',
                    'pengalaman' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Attach some existing skills if present in DB (best-effort)
                $position = $static['judul_posisi'];
                $skillNames = [];
                if ($position === 'Frontend Developer') {
                    $skillNames = ['JavaScript', 'HTML/CSS', 'React'];
                } elseif ($position === 'Data Analyst') {
                    $skillNames = ['Python', 'SQL', 'Data Visualization'];
                }

                $foundSkills = DB::table('keahlian')->whereIn('nama_keahlian', $skillNames)->get();
                $skillInserts = [];
                foreach ($foundSkills as $s) {
                    $skillInserts[] = [
                        'lowongan_id' => $id,
                        'keahlian_id' => $s->keahlian_id,
                        'kemampuan_minimum' => 'pemula',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                if (!empty($skillInserts)) {
                    DB::table('keahlian_lowongan')->insert($skillInserts);
                }
            }
        }
    }
}
