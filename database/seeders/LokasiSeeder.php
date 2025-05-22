<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lokasi;

class LokasiSeeder extends Seeder
{
    public function run()
    {
        $alamatList = [
            ['Jalan Tugu, RW. 08, KEL. KIDUL DALEM, KOTA MALANG, Malang 65111, Indonesia',  -7.97700000,  112.62800000],
            ['Jalan Basuki Rahmat No.1, Surabaya, 60264, Indonesia',  -7.27300000,  112.73400000],
            ['Jalan Ijen No.2, Malang, 65111, Indonesia',  -7.97700000,  112.62800000],
            ['Jalan Sultan Agung No.3, Kediri, 64111, Indonesia',  -7.81300000,  112.01100000],
            ['Jalan Panglima Sudirman No.4, Jember, 68111, Indonesia',  -8.17300000,  113.70200000],
            ['Jalan Soekarno Hatta No.5, Banyuwangi, 68411, Indonesia',  -8.22400000,  114.38300000],
            ['Jalan Diponegoro No.6, Mojokerto, 61311, Indonesia',  -7.46200000,  112.43500000],
            ['Jalan Dr. Soetomo No.7, Madiun, 63111, Indonesia',  -7.62300000,  111.52100000],
            ['Jalan Gajah Mada No.8, Probolinggo, 67211, Indonesia',  -7.75500000,  113.21500000],
            ['Jalan Hayam Wuruk No.9, Blitar, 66111, Indonesia',  -8.09700000,  112.16400000],
            ['Jalan Wahid Hasyim No.10, Pasuruan, 67111, Indonesia',  -7.64400000,  112.90200000],
            ['Jalan KH Hasyim Asy’ari No.11, Sidoarjo, 61211, Indonesia',  -7.44900000,  112.67600000],
            ['Jalan Pahlawan No.12, Tuban, 62311, Indonesia',  -6.89500000,  112.05000000],
            ['Jalan Trunojoyo No.13, Bangkalan, 69111, Indonesia',  -7.05900000,  112.73700000],
            ['Jalan Rajawali No.14, Situbondo, 68311, Indonesia',  -7.70200000,  114.09800000],
            ['Jalan Arief Rahman Hakim No.15, Ngawi, 63211, Indonesia',  -7.40500000,  111.44400000],
            ['Jalan Imam Bonjol No.16, Nganjuk, 64411, Indonesia',  -7.60300000,  112.05400000],
            ['Jalan Merdeka No.17, Lamongan, 62211, Indonesia',  -7.11300000,  112.37500000],
            ['Jalan Kartini No.18, Gresik, 61111, Indonesia',  -7.14900000,  112.65500000],
            ['Jalan Letjen S. Parman No.19, Bojonegoro, 62111, Indonesia',  -7.15300000,  111.88300000],
            ['Jalan Mayjen Sungkono No.20, Magetan, 66311, Indonesia',  -7.65300000,  111.32800000],
            ['Jalan Teuku Umar No.21, Tulungagung, 66211, Indonesia',  -8.05900000,  111.90200000],
            ['Jalan Sunan Ampel No.22, Sumenep, 69411, Indonesia',  -7.02300000,  113.70400000],
            ['Jalan Dr. Wahidin No.23, Pamekasan, 69311, Indonesia',  -7.17000000,  113.47200000],
            ['Jalan Pattimura No.24, Trenggalek, 66311, Indonesia',  -8.30900000,  111.70900000],
            ['Jalan Ahmad Yani No.25, Pacitan, 63511, Indonesia',  -8.06500000,  110.74100000],
        ];

        $lokasiList = Lokasi::all();

        foreach ($lokasiList as $lokasi) {
            $index = $lokasi->lokasi_id - 1;

            $lokasi->update([
                'alamat' => $alamatList[$index % count($alamatList)][0],
                'latitude' => $alamatList[$index % count($alamatList)][1],
                'longitude' => $alamatList[$index % count($alamatList)][2],
            ]);
        }
    }
}
