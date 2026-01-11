<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if table already has data
        if (DB::table('provinsi')->count() > 0) {
            $this->command->info('Tabel provinsi sudah berisi data. Dilewati.');
            return;
        }

        // Data provinsi dari file SQL
        $provinsiData = [
            [1, '11', 'Aceh', 4.69513500, 96.74939700, NULL, NULL],
            [2, '12', 'Sumatera Utara', 3.59519500, 98.67222200, NULL, NULL],
            [3, '13', 'Sumatera Barat', -0.94924000, 100.35427800, NULL, NULL],
            [4, '14', 'Riau', 0.29334700, 101.70682900, NULL, NULL],
            [5, '15', 'Jambi', -1.61012300, 103.61312000, NULL, NULL],
            [6, '16', 'Sumatera Selatan', -3.31943700, 103.91439900, NULL, NULL],
            [7, '17', 'Bengkulu', -3.79284500, 102.26076500, NULL, NULL],
            [8, '18', 'Lampung', -5.42966500, 105.26202400, NULL, NULL],
            [9, '19', 'Kepulauan Bangka Belitung', -2.13332900, 106.11666900, NULL, NULL],
            [10, '21', 'Kepulauan Riau', 0.91666600, 104.46666700, NULL, NULL],
            [11, '31', 'DKI Jakarta', -6.20876300, 106.84559900, NULL, NULL],
            [12, '32', 'Jawa Barat', -6.91746400, 107.61912300, NULL, NULL],
            [13, '33', 'Jawa Tengah', -6.96666700, 110.41666400, NULL, NULL],
            [14, '34', 'DI Yogyakarta', -7.79557900, 110.36949200, NULL, NULL],
            [15, '35', 'Jawa Timur', -7.25044500, 112.76884500, NULL, NULL],
            [16, '36', 'Banten', -6.40581700, 106.06401800, NULL, NULL],
            [17, '51', 'Bali', -8.67045800, 115.21263100, NULL, NULL],
            [18, '52', 'Nusa Tenggara Barat', -8.58333300, 116.11666900, NULL, NULL],
            [19, '53', 'Nusa Tenggara Timur', -8.65738200, 121.07937000, NULL, NULL],
            [20, '61', 'Kalimantan Barat', -0.02633000, 109.34250600, NULL, NULL],
            [21, '62', 'Kalimantan Tengah', -2.21666700, 113.91666400, NULL, NULL],
            [22, '63', 'Kalimantan Selatan', -3.31472400, 114.59249900, NULL, NULL],
            [23, '64', 'Kalimantan Timur', 0.50222300, 117.15361000, NULL, NULL],
            [24, '65', 'Kalimantan Utara', 3.31669400, 117.59094200, NULL, NULL],
            [25, '71', 'Sulawesi Utara', 1.47483000, 124.84207900, NULL, NULL],
            [26, '72', 'Sulawesi Tengah', -0.90000000, 119.86666900, NULL, NULL],
            [27, '73', 'Sulawesi Selatan', -5.14766500, 119.43215900, NULL, NULL],
            [28, '74', 'Sulawesi Tenggara', -3.96811900, 122.59693900, NULL, NULL],
            [29, '75', 'Gorontalo', 0.54111100, 123.05944100, NULL, NULL],
            [30, '76', 'Sulawesi Barat', -2.66840300, 118.88531500, NULL, NULL],
            [31, '81', 'Maluku', -3.23846200, 130.14526900, NULL, NULL],
            [32, '82', 'Maluku Utara', 0.78927500, 127.38415500, NULL, NULL],
            [33, '91', 'Papua Barat', -0.86145300, 134.06204200, NULL, NULL],
            [34, '92', 'Papua Barat Daya', -0.87652300, 131.25528000, NULL, NULL],
            [35, '93', 'Papua Selatan', -8.49911200, 140.40498400, NULL, NULL],
            [36, '94', 'Papua', -2.57422200, 140.64694400, NULL, NULL],
            [37, '95', 'Papua Tengah', -3.36357400, 135.49504100, NULL, NULL],
            [38, '96', 'Papua Pegunungan', -4.02410300, 138.94825700, NULL, NULL],
        ];

        foreach ($provinsiData as $data) {
            DB::table('provinsi')->insert([
                'id' => $data[0],
                'kode_provinsi' => $data[1],
                'name' => $data[2],
                'latitude' => $data[3],
                'longitude' => $data[4],
                'created_at' => $data[5],
                'updated_at' => $data[6],
            ]);
        }

        // Set auto increment
        DB::statement('ALTER TABLE provinsi AUTO_INCREMENT = 39');

        $this->command->info('Data provinsi berhasil diimpor (' . count($provinsiData) . ' records)');
    }
}
