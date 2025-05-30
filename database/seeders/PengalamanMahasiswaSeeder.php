<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengalamanMahasiswaSeeder extends Seeder
{
    protected function getExperienceDescription($type)
    {
        if ($type == 'lomba') {
            $descriptions = [
                'Berpartisipasi dalam kompetisi nasional bidang teknologi',
                'Memenangkan penghargaan dalam kompetisi desain',
                'Mengembangkan solusi inovatif dalam kompetisi hackathon',
                'Berhasil mencapai babak final dalam kompetisi programming'
            ];
        } else {
            $descriptions = [
                'Bekerja sebagai developer untuk membangun aplikasi web',
                'Membantu tim dalam pengembangan produk digital',
                'Melakukan riset dan pengembangan fitur baru',
                'Bertanggung jawab atas desain antarmuka pengguna'
            ];
        }
        return $descriptions[array_rand($descriptions)];
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswaIds = DB::table('profil_mahasiswa')->pluck('mahasiswa_id');
        $keahlianIds = DB::table('keahlian')->pluck('keahlian_id');

        $experienceTypes = ['lomba', 'kerja'];
        $competitionNames = [
            'Hackathon Nasional 2022',
            'Competitive Programming Competition',
            'UI/UX Design Challenge',
            'Data Science Olympiad'
        ];
        $jobTitles = [
            'Freelance Web Developer',
            'Internship at Startup',
            'Part-time Designer',
            'Research Assistant'
        ];

        foreach ($mahasiswaIds as $mhsId) {
            $jumlahPengalaman = rand(1, 4);

            for ($i = 0; $i < $jumlahPengalaman; $i++) {
                $type = $experienceTypes[rand(0, 1)];
                $startDate = now()->subMonths(rand(3, 24));
                $endDate = $startDate->copy()->addMonths(rand(1, 12));

                $pengalamanId = DB::table('pengalaman_mahasiswa')->insertGetId([
                    'mahasiswa_id' => $mhsId,
                    'nama_pengalaman' => $type == 'lomba'
                        ? $competitionNames[rand(0, count($competitionNames) - 1)]
                        : $jobTitles[rand(0, count($jobTitles) - 1)],
                    'tipe_pengalaman' => $type,
                    'path_file' => $type == 'lomba'
                        ?   'pengalaman/sertifikat_' . $mhsId . '_' . ($i + 1) . '.pdf' : '',
                    'deskripsi_pengalaman' => $this->getExperienceDescription($type),
                    'periode_mulai' => $type == 'kerja' ? $startDate->format('Y-m-d') : null,
                    'periode_selesai' => $type == 'kerja' ? $endDate->format('Y-m-d') : null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $jumlahTag = rand(1, 3);
                $keahlianTerpilih = array_rand($keahlianIds->toArray(), $jumlahTag);

                if (!is_array($keahlianTerpilih)) {
                    $keahlianTerpilih = [$keahlianTerpilih];
                }

                foreach ($keahlianTerpilih as $keahlianIndex) {
                    DB::table('pengalaman_tag')->insert([
                        'pengalaman_id' => $pengalamanId,
                        'keahlian_id' => $keahlianIds[$keahlianIndex],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
    }
}
