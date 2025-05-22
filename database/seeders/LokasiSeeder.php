<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lokasi;

class LokasiSeeder extends Seeder
{
    public function run()
    {
        $alamatList = [
            'Jl. Basuki Rahmat No.1, Surabaya',
            'Jl. Ijen No.2, Malang',
            'Jl. Sultan Agung No.3, Kediri',
            'Jl. Panglima Sudirman No.4, Jember',
            'Jl. Soekarno Hatta No.5, Banyuwangi',
            'Jl. Diponegoro No.6, Mojokerto',
            'Jl. Dr. Soetomo No.7, Madiun',
            'Jl. Gajah Mada No.8, Probolinggo',
            'Jl. Hayam Wuruk No.9, Blitar',
            'Jl. Wahid Hasyim No.10, Pasuruan',
            'Jl. KH Hasyim Asy’ari No.11, Sidoarjo',
            'Jl. Pahlawan No.12, Tuban',
            'Jl. Trunojoyo No.13, Bangkalan',
            'Jl. Rajawali No.14, Situbondo',
            'Jl. Arief Rahman Hakim No.15, Ngawi',
            'Jl. Imam Bonjol No.16, Nganjuk',
            'Jl. Merdeka No.17, Lamongan',
            'Jl. Kartini No.18, Gresik',
            'Jl. Letjen S. Parman No.19, Bojonegoro',
            'Jl. Mayjen Sungkono No.20, Magetan',
            'Jl. Teuku Umar No.21, Tulungagung',
            'Jl. Sunan Ampel No.22, Sumenep',
            'Jl. Dr. Wahidin No.23, Pamekasan',
            'Jl. Pattimura No.24, Trenggalek',
            'Jl. Ahmad Yani No.25, Pacitan',
        ];

        foreach ($alamatList as $index => $alamatBaru) {
            $lokasiId = $index + 1;

            Lokasi::where('lokasi_id', $lokasiId)
                ->update(['alamat' => $alamatBaru]);
        }
    }
}
