<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        return view('kelas.index', compact('kelas'));
    }

    // Dipanggil manual / via route
    public function updateStatus()
    {
        $kelasList = Kelas::with('peminjaman')->get();

        foreach ($kelasList as $kelas) {
            $peminjamanAktif = $kelas->peminjaman()
                ->where('status', 'disetujui')
                ->where('jam_mulai', '<=', now())
                ->where('jam_selesai', '>', now())
                ->first();

            if ($peminjamanAktif) {
                // Akan selesai (30 menit lagi)
                if ($peminjamanAktif->jam_selesai <= now()->addMinutes(30)) {
                    $kelas->status = 'akan_selesai';
                } else {
                    $kelas->status = 'dipinjam';
                }
            } else {
                $kelas->status = 'tersedia';
            }

            $kelas->save();
        }

        return response()->json(['message' => 'Status kelas berhasil diperbarui']);
    }
}
