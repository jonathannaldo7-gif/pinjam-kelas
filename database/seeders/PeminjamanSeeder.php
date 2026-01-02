<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use Carbon\Carbon;

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $peminjaman = [
            // Pending bookings for admin to approve/reject
            [
                'user_id' => 1, // User mahasiswa (actually admin for testing)
                'kelas_id' => 1, // Ruang Kuliah 101
                'kode_matkul' => 'TI101 - Algoritma (Dr. John Doe)',
                'keterangan' => 'Kelas pengganti untuk praktikum algoritma',
                'tanggal' => Carbon::tomorrow()->format('Y-m-d'),
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00',
                'status' => 'menunggu',
            ],
            [
                'user_id' => 2,
                'kelas_id' => 2, // Ruang Kuliah 102
                'kode_matkul' => 'SI201 - Basis Data',
                'keterangan' => 'Kegiatan organisasi BEM',
                'tanggal' => Carbon::tomorrow()->addDays(1)->format('Y-m-d'),
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00',
                'status' => 'menunggu',
            ],
            [
                'user_id' => 2,
                'kelas_id' => 3, // Ruang Kuliah 103
                'kode_matkul' => null, // No course code for organization activity
                'keterangan' => 'Rapat internal Himpunan Mahasiswa',
                'tanggal' => Carbon::tomorrow()->addDays(2)->format('Y-m-d'),
                'jam_mulai' => '16:00:00',
                'jam_selesai' => '18:00:00',
                'status' => 'menunggu',
            ],

            // Already approved bookings
            [
                'user_id' => 2,
                'kelas_id' => 4, // Ruang Kuliah 201
                'kode_matkul' => 'TI202 - Struktur Data',
                'keterangan' => 'Praktikum struktur data minggu ke-5',
                'tanggal' => Carbon::yesterday()->format('Y-m-d'),
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '11:00:00',
                'status' => 'disetujui',
            ],

            // Rejected booking
            [
                'user_id' => 2,
                'kelas_id' => 5, // Ruang Kuliah 202
                'kode_matkul' => 'SI301 - Sistem Operasi',
                'keterangan' => 'Kuliah tamu dengan dosen luar',
                'tanggal' => Carbon::yesterday()->subDays(1)->format('Y-m-d'),
                'jam_mulai' => '14:00:00',
                'jam_selesai' => '16:00:00',
                'status' => 'ditolak',
            ],
        ];

        foreach ($peminjaman as $data) {
            Peminjaman::create($data);
        }
    }
}
