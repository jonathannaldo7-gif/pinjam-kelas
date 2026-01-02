<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = [
            // Lantai 1 - Ruang 1-4
            ['nama_kelas' => 'Lt 1 Ruang 1', 'code_kelas' => 'KTT 1.01', 'kapasitas' => 50],
            ['nama_kelas' => 'Lt 1 Ruang 2', 'code_kelas' => 'KTT 1.02', 'kapasitas' => 50],
            ['nama_kelas' => 'Lt 1 Ruang 3', 'code_kelas' => 'KTT 1.03', 'kapasitas' => 50],
            ['nama_kelas' => 'Lt 1 Ruang 4', 'code_kelas' => 'KTT 1.04', 'kapasitas' => 50],

            // Lantai 2 - Ruang 1-2
            ['nama_kelas' => 'Lt 2 Ruang 1', 'code_kelas' => 'KTT 2.01', 'kapasitas' => 45],
            ['nama_kelas' => 'Lt 2 Ruang 2', 'code_kelas' => 'KTT 2.02', 'kapasitas' => 45],

            // Laboratorium
            ['nama_kelas' => 'Cyber Lab', 'code_kelas' => 'CYCLAB.LAB', 'kapasitas' => 30],
            ['nama_kelas' => 'Sistem Enterprise Lab', 'code_kelas' => 'SE.LAB', 'kapasitas' => 25],

            // Aula
            ['nama_kelas' => 'Aula Utama', 'code_kelas' => 'AULA', 'kapasitas' => 200],
        ];

        foreach ($kelas as $data) {
            Kelas::create($data);
        }
    }
}
