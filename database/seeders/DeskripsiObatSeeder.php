<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class DeskripsiObatSeeder extends Seeder
{
    public function run(): void
    {
        $csv = Reader::createFromPath(
            database_path('seeders/data/deskripsi_obat_alat_kesehatan.csv'),
            'r'
        );

        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            if ($row['nilai'] != '-') {
                DB::table('deskripsi_obat')->insert([
                    'obat_id'    => $row['obat_id'],
                    'label'      => $row['label'],
                    'nilai'      => $row['nilai'],
                    'urutan'     => $row['urutan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $csv = Reader::createFromPath(
            database_path('seeders/data/deskripsi_obat_makanan_dan_minuman.csv'),
            'r'
        );

        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            if ($row['nilai'] != '-') {
                DB::table('deskripsi_obat')->insert([
                    'obat_id'    => $row['obat_id'],
                    'label'      => $row['label'],
                    'nilai'      => $row['nilai'],
                    'urutan'     => $row['urutan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $csv = Reader::createFromPath(
            database_path('seeders/data/deskripsi_obat_obat_bebas.csv'),
            'r'
        );

        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            if ($row['nilai'] != '-') {
                DB::table('deskripsi_obat')->insert([
                    'obat_id'    => $row['obat_id'],
                    'label'      => $row['label'],
                    'nilai'      => $row['nilai'],
                    'urutan'     => $row['urutan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $csv = Reader::createFromPath(
            database_path('seeders/data/deskripsi_obat_perawatan_tubuh.csv'),
            'r'
        );

        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            if ($row['nilai'] != '-') {
                DB::table('deskripsi_obat')->insert([
                    'obat_id'    => $row['obat_id'],
                    'label'      => $row['label'],
                    'nilai'      => $row['nilai'],
                    'urutan'     => $row['urutan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $csv = Reader::createFromPath(
            database_path('seeders/data/deskripsi_obat_vitamin_dan_suplemen.csv'),
            'r'
        );

        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            if ($row['nilai'] != '-') {
                DB::table('deskripsi_obat')->insert([
                    'obat_id'    => $row['obat_id'],
                    'label'      => $row['label'],
                    'nilai'      => $row['nilai'],
                    'urutan'     => $row['urutan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
