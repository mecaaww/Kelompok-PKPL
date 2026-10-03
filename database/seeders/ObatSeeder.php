<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class ObatSeeder extends Seeder
{
    public function run(): void
    {
        $csv = Reader::createFromPath(
            database_path('seeders/data/obat_alat_kesehatan.csv'),
            'r'
        );

        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            DB::table('obat')->insert([
                'nama'          => $row['nama'],
                'harga'         => $row['harga'],
                'diskon_persen' => $row['diskon_persen'],
                'kategori'      => $row['kategori'],
                'path_gambar'   => $row['path_gambar'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        $csv = Reader::createFromPath(
            database_path('seeders/data/obat_makanan_dan_minuman.csv'),
            'r'
        );

        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            DB::table('obat')->insert([
                'nama'          => $row['nama'],
                'harga'         => $row['harga'],
                'diskon_persen' => $row['diskon_persen'],
                'kategori'      => $row['kategori'],
                'path_gambar'   => $row['path_gambar'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        $csv = Reader::createFromPath(
            database_path('seeders/data/obat_obat_bebas.csv'),
            'r'
        );

        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            DB::table('obat')->insert([
                'nama'          => $row['nama'],
                'harga'         => $row['harga'],
                'diskon_persen' => $row['diskon_persen'],
                'kategori'      => $row['kategori'],
                'path_gambar'   => $row['path_gambar'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        $csv = Reader::createFromPath(
            database_path('seeders/data/obat_perawatan_tubuh.csv'),
            'r'
        );

        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            DB::table('obat')->insert([
                'nama'          => $row['nama'],
                'harga'         => $row['harga'],
                'diskon_persen' => $row['diskon_persen'],
                'kategori'      => $row['kategori'],
                'path_gambar'   => $row['path_gambar'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        $csv = Reader::createFromPath(
            database_path('seeders/data/obat_vitamin_dan_suplemen.csv'),
            'r'
        );

        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            DB::table('obat')->insert([
                'nama'          => $row['nama'],
                'harga'         => $row['harga'],
                'diskon_persen' => $row['diskon_persen'],
                'kategori'      => $row['kategori'],
                'path_gambar'   => $row['path_gambar'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
