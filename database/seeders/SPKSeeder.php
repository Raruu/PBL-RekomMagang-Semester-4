<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SPKSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bobot = [
            [
                'bobot' => 0.2,
                'jenis_bobot' => 'IPK',
                // 'bobot_prev' => 0.3,
                // 'jenis_bobot_prev' => 'IPK'
            ],
            [
                'bobot' => 0.25,
                'jenis_bobot' => 'keahlian',
                // 'bobot_prev' => 0.2,
                // 'jenis_bobot_prev' => 'keahlian'
            ],
            [
                'bobot' => 0.2,
                'jenis_bobot' => 'pengalaman',
                // 'bobot_prev' => 0.2,
                // 'jenis_bobot_prev' => 'pengalaman'
            ],
            [
                'bobot' => 0.1,
                'jenis_bobot' => 'jarak',
                // 'bobot_prev' => 0.1,
                // 'jenis_bobot_prev' => 'jarak'
            ],
            [
                'bobot' => 0.25,
                'jenis_bobot' => 'posisi',
                // 'bobot_prev' => 0.2,
                // 'jenis_bobot_prev' => 'posisi'
            ]
        ];
        DB::table('bobot_spk')->insert($bobot);
    }
}
