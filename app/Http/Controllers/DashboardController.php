<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        $peminjaman = Peminjaman::with(['kelas','user'])->get();

        return view('dashboard.index', compact('kelas','peminjaman'));
    }
}
