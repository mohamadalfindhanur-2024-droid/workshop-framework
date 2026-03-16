<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        // ===================== PROVINSI =====================
        $provinsi = [
            ['id' => '11', 'nama' => 'ACEH'],
            ['id' => '12', 'nama' => 'SUMATERA UTARA'],
            ['id' => '32', 'nama' => 'JAWA BARAT'],
            ['id' => '33', 'nama' => 'JAWA TENGAH'],
            ['id' => '34', 'nama' => 'DI YOGYAKARTA'],
            ['id' => '35', 'nama' => 'JAWA TIMUR'],
            ['id' => '64', 'nama' => 'KALIMANTAN TIMUR'],
            ['id' => '73', 'nama' => 'SULAWESI SELATAN'],
        ];
        DB::table('provinsi')->insertOrIgnore($provinsi);

        // ===================== KOTA =====================
        $kota = [
            // Jawa Barat
            ['id' => '3201', 'nama' => 'KAB. BOGOR',     'id_provinsi' => '32'],
            ['id' => '3273', 'nama' => 'KOTA BANDUNG',   'id_provinsi' => '32'],
            ['id' => '3275', 'nama' => 'KOTA BEKASI',    'id_provinsi' => '32'],
            // Jawa Tengah
            ['id' => '3310', 'nama' => 'KAB. KLATEN',    'id_provinsi' => '33'],
            ['id' => '3374', 'nama' => 'KOTA SEMARANG',  'id_provinsi' => '33'],
            ['id' => '3376', 'nama' => 'KOTA MAGELANG',  'id_provinsi' => '33'],
            // DI Yogyakarta
            ['id' => '3401', 'nama' => 'KAB. KULON PROGO', 'id_provinsi' => '34'],
            ['id' => '3471', 'nama' => 'KOTA YOGYAKARTA',  'id_provinsi' => '34'],
            // Jawa Timur
            ['id' => '3507', 'nama' => 'KAB. MALANG',    'id_provinsi' => '35'],
            ['id' => '3578', 'nama' => 'KOTA SURABAYA',  'id_provinsi' => '35'],
            ['id' => '3573', 'nama' => 'KOTA MALANG',    'id_provinsi' => '35'],
            // Aceh
            ['id' => '1101', 'nama' => 'KAB. SIMEULUE',  'id_provinsi' => '11'],
            ['id' => '1171', 'nama' => 'KOTA BANDA ACEH','id_provinsi' => '11'],
            // Sumatera Utara
            ['id' => '1271', 'nama' => 'KOTA MEDAN',     'id_provinsi' => '12'],
            ['id' => '1272', 'nama' => 'KOTA PEMATANG SIANTAR', 'id_provinsi' => '12'],
            // Kalimantan Timur
            ['id' => '6471', 'nama' => 'KOTA SAMARINDA', 'id_provinsi' => '64'],
            ['id' => '6472', 'nama' => 'KOTA BALIKPAPAN','id_provinsi' => '64'],
            // Sulawesi Selatan
            ['id' => '7371', 'nama' => 'KOTA MAKASSAR',  'id_provinsi' => '73'],
            ['id' => '7373', 'nama' => 'KOTA PALOPO',    'id_provinsi' => '73'],
        ];
        DB::table('kota')->insertOrIgnore($kota);

        // ===================== KECAMATAN =====================
        $kecamatan = [
            // Kota Bandung
            ['id' => '327301', 'nama' => 'SUKASARI',       'id_kota' => '3273'],
            ['id' => '327302', 'nama' => 'COBLONG',        'id_kota' => '3273'],
            ['id' => '327303', 'nama' => 'CICENDO',        'id_kota' => '3273'],
            // Kota Semarang
            ['id' => '337401', 'nama' => 'MIJEN',          'id_kota' => '3374'],
            ['id' => '337402', 'nama' => 'GUNUNGPATI',     'id_kota' => '3374'],
            ['id' => '337403', 'nama' => 'BANYUMANIK',     'id_kota' => '3374'],
            // Kota Yogyakarta
            ['id' => '347101', 'nama' => 'DANUREJAN',      'id_kota' => '3471'],
            ['id' => '347102', 'nama' => 'GEDONGTENGEN',   'id_kota' => '3471'],
            ['id' => '347103', 'nama' => 'GONDOKUSUMAN',   'id_kota' => '3471'],
            // Kota Surabaya
            ['id' => '357801', 'nama' => 'KARANG PILANG',  'id_kota' => '3578'],
            ['id' => '357802', 'nama' => 'JAMBANGAN',      'id_kota' => '3578'],
            ['id' => '357803', 'nama' => 'GAYUNGAN',       'id_kota' => '3578'],
            // Kota Malang
            ['id' => '357301', 'nama' => 'KEDUNGKANDANG',  'id_kota' => '3573'],
            ['id' => '357302', 'nama' => 'SUKUN',          'id_kota' => '3573'],
            ['id' => '357303', 'nama' => 'KLOJEN',         'id_kota' => '3573'],
            // Kota Medan
            ['id' => '127101', 'nama' => 'MEDAN TUNTUNGAN','id_kota' => '1271'],
            ['id' => '127102', 'nama' => 'MEDAN JOHOR',    'id_kota' => '1271'],
            ['id' => '127103', 'nama' => 'MEDAN AMPLAS',   'id_kota' => '1271'],
            // Kota Makassar
            ['id' => '737101', 'nama' => 'MARISO',         'id_kota' => '7371'],
            ['id' => '737102', 'nama' => 'MAMAJANG',       'id_kota' => '7371'],
            ['id' => '737103', 'nama' => 'TAMALATE',       'id_kota' => '7371'],
            // Kota Samarinda
            ['id' => '647101', 'nama' => 'PALARAN',        'id_kota' => '6471'],
            ['id' => '647102', 'nama' => 'SAMARINDA ILIR', 'id_kota' => '6471'],
            ['id' => '647103', 'nama' => 'SAMARINDA KOTA', 'id_kota' => '6471'],
        ];
        DB::table('kecamatan')->insertOrIgnore($kecamatan);

        // ===================== KELURAHAN =====================
        $kelurahan = [
            // Sukasari - Bandung
            ['id' => '3273010001', 'nama' => 'SUKAWARNA',     'id_kecamatan' => '327301'],
            ['id' => '3273010002', 'nama' => 'SUKARASA',      'id_kecamatan' => '327301'],
            ['id' => '3273010003', 'nama' => 'ISOLA',         'id_kecamatan' => '327301'],
            // Coblong - Bandung
            ['id' => '3273020001', 'nama' => 'CIPAGANTI',     'id_kecamatan' => '327302'],
            ['id' => '3273020002', 'nama' => 'LEBAK SILIWANGI','id_kecamatan' => '327302'],
            ['id' => '3273020003', 'nama' => 'DAGO',          'id_kecamatan' => '327302'],
            // Cicendo - Bandung
            ['id' => '3273030001', 'nama' => 'HUSEIN SASTRANEGARA', 'id_kecamatan' => '327303'],
            ['id' => '3273030002', 'nama' => 'PAMOYANAN',     'id_kecamatan' => '327303'],
            // Mijen - Semarang
            ['id' => '3374010001', 'nama' => 'CANGKIRAN',     'id_kecamatan' => '337401'],
            ['id' => '3374010002', 'nama' => 'BUBAKAN',       'id_kecamatan' => '337401'],
            ['id' => '3374010003', 'nama' => 'KARANG MALANG', 'id_kecamatan' => '337401'],
            // Gunungpati - Semarang
            ['id' => '3374020001', 'nama' => 'GUNUNGPATI',    'id_kecamatan' => '337402'],
            ['id' => '3374020002', 'nama' => 'PLALANGAN',     'id_kecamatan' => '337402'],
            // Banyumanik - Semarang
            ['id' => '3374030001', 'nama' => 'SRONDOL WETAN', 'id_kecamatan' => '337403'],
            ['id' => '3374030002', 'nama' => 'PEDALANGAN',    'id_kecamatan' => '337403'],
            // Danurejan - Yogyakarta
            ['id' => '3471010001', 'nama' => 'SURYATMAJAN',   'id_kecamatan' => '347101'],
            ['id' => '3471010002', 'nama' => 'TEGALPANGGUNG', 'id_kecamatan' => '347101'],
            // Karang Pilang - Surabaya
            ['id' => '3578010001', 'nama' => 'KEBRAON',       'id_kecamatan' => '357801'],
            ['id' => '3578010002', 'nama' => 'KARANG PILANG', 'id_kecamatan' => '357801'],
            ['id' => '3578010003', 'nama' => 'WARU GUNUNG',   'id_kecamatan' => '357801'],
            // Jambangan - Surabaya
            ['id' => '3578020001', 'nama' => 'JAMBANGAN',     'id_kecamatan' => '357802'],
            ['id' => '3578020002', 'nama' => 'KEBONSARI',     'id_kecamatan' => '357802'],
            // Kedungkandang - Malang
            ['id' => '3573010001', 'nama' => 'ARJOWILANGUN',  'id_kecamatan' => '357301'],
            ['id' => '3573010002', 'nama' => 'BUMIAYU',       'id_kecamatan' => '357301'],
            // Medan Tuntungan
            ['id' => '1271010001', 'nama' => 'SIDOMULYO',     'id_kecamatan' => '127101'],
            ['id' => '1271010002', 'nama' => 'NAMO GAJAH',    'id_kecamatan' => '127101'],
            // Mariso - Makassar
            ['id' => '7371010001', 'nama' => 'LETTE',         'id_kecamatan' => '737101'],
            ['id' => '7371010002', 'nama' => 'MARISO',        'id_kecamatan' => '737101'],
            // Palaran - Samarinda
            ['id' => '6471010001', 'nama' => 'BUKUAN',        'id_kecamatan' => '647101'],
            ['id' => '6471010002', 'nama' => 'RAWA MAKMUR',   'id_kecamatan' => '647101'],
        ];
        DB::table('kelurahan')->insertOrIgnore($kelurahan);

        $this->command->info('Wilayah data seeded successfully!');
    }
}
