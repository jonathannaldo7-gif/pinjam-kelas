<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin default - for testing admin functions
        User::create([
            'nim'           => '00000001',
            'nama_lengkap'  => 'Admin Sistem',
            'prodi'         => 'Sistem Informasi',
            'email'         => 'admin@kampus.ac.id',
            'password'      => Hash::make('password'),
            'role'          => 'admin',
        ]);

        // Seed kelas - populate classrooms
        $this->call([
            KelasSeeder::class,
        ]);
    }
}
