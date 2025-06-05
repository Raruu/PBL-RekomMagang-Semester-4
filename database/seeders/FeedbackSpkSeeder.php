<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FeedbackSpkSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $ratings = [1, 2, 3, 4, 5];

        $comments = [
            'Sangat membantu dalam pemilihan SPK',
            'User interface perlu diperbaiki',
            'Rekomendasi yang diberikan cukup akurat',
            'Sistem berjalan dengan lancar',
            'Butuh lebih banyak alternatif rekomendasi',
            'Prosesnya terlalu panjang',
            'Mudah digunakan dan dipahami',
            'Hasil rekomendasi tidak sesuai ekspektasi',
            'Sangat puas dengan sistem ini',
            'Perlu penambahan fitur filter',
            'The system works great!',
            'Could be more intuitive',
            'Very helpful for decision making',
            'Needs more customization options',
            'Overall good experience'
        ];

        $minIndex = DB::table('profil_mahasiswa')->min('mahasiswa_id');

        for ($i = 1; $i <= 30; $i++) {
            DB::table('feedback_spk')->insert([
                'mahasiswa_id' => $i + $minIndex,
                'rating' => $faker->randomElement($ratings),
                'komentar' => $faker->randomElement([null, $faker->randomElement($comments)]),
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
