<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $peminjaman = Peminjaman::with('kelas')->latest()->get();
        } else {
            $peminjaman = Peminjaman::with('kelas')->where('user_id', Auth::id())->latest()->get();
        }

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function dashboard()
    {
        $kelas = Kelas::withCount([
            'peminjaman as jumlah_peminjaman' => function ($q) {
                $q->whereIn('status', ['menunggu','disetujui']);
            }
        ])->get();

        // Limit remaining quota to maximum 10
        foreach ($kelas as $k) {
            $remaining = $k->kapasitas - $k->jumlah_peminjaman;
            $k->kuota_tersisa = min($remaining, 10);
        }

        // Calculate real-time status for each class based on current bookings
        $currentDate = now()->toDateString();
        $currentTime = now()->toTimeString();

        foreach ($kelas as $k) {
            $currentBookings = Peminjaman::where('kelas_id', $k->id)
                ->where('tanggal', $currentDate)
                ->where('status', 'disetujui')
                ->where('jam_mulai', '<=', $currentTime)
                ->where('jam_selesai', '>', $currentTime)
                ->count();

            $upcomingBookings = Peminjaman::where('kelas_id', $k->id)
                ->where('tanggal', $currentDate)
                ->where('status', 'disetujui')
                ->where('jam_mulai', '>', $currentTime)
                ->where('jam_mulai', '<=', now()->addMinutes(30)->toTimeString()) // within next 30 minutes
                ->count();

            // Check if ANY user has an active approved booking for this classroom
            $anyActiveBooking = Peminjaman::where('kelas_id', $k->id)
                ->where('status', 'disetujui')
                ->where(function($query) use ($currentDate, $currentTime) {
                    // Booking is still active if:
                    // 1. Date is today or future, OR
                    // 2. Date is today but end time hasn't passed
                    $query->where('tanggal', '>', $currentDate) // future dates
                          ->orWhere(function($q) use ($currentDate, $currentTime) {
                              $q->where('tanggal', '=', $currentDate) // today
                                ->where('jam_selesai', '>', $currentTime); // end time hasn't passed
                          });
                })
                ->exists();

            if ($anyActiveBooking) {
                $k->real_time_status = 'occupied'; // merah - sedang dipakai (oleh siapa saja)
                $k->status_message = 'Sedang Digunakan';
            } elseif ($upcomingBookings > 0) {
                $k->real_time_status = 'warning'; // kuning - akan digunakan
                $k->status_message = 'Akan Digunakan';
            } else {
                $k->real_time_status = 'available'; // hijau - tersedia
                $k->status_message = 'Tersedia';
            }

            // Additional check: if THIS user has already booked this classroom (regardless of time)
            $userBookedThisClassroom = Peminjaman::where('user_id', Auth::id())
                ->where('kelas_id', $k->id)
                ->where('status', 'disetujui')
                ->exists();

            if ($userBookedThisClassroom) {
                $k->real_time_status = 'user_has_booking';
                $k->status_message = 'Sudah Booking';
                $k->kuota_tersisa = 0; // Set quota to 0 for this specific classroom
            }
        }

        // Separate current bookings from history
        // Current bookings: pending + approved bookings that haven't expired yet
        $currentDate = now()->toDateString();
        $currentTime = now()->toTimeString();

        $peminjaman = Peminjaman::with('kelas')
            ->where('user_id', Auth::id())
            ->where(function($query) use ($currentDate, $currentTime) {
                $query->where('status', 'menunggu') // pending bookings always show
                      ->orWhere(function($q) use ($currentDate, $currentTime) {
                          $q->where('status', 'disetujui') // approved bookings that haven't expired
                            ->where(function($innerQuery) use ($currentDate, $currentTime) {
                                $innerQuery->where('tanggal', '>', $currentDate) // future dates
                                          ->orWhere(function($futureQuery) use ($currentDate, $currentTime) {
                                              $futureQuery->where('tanggal', '=', $currentDate) // today
                                                          ->where('jam_selesai', '>', $currentTime); // end time hasn't passed
                                          });
                            });
                      });
            })
            ->latest()
            ->get();

        // History: rejected + completed bookings
        $riwayatPeminjaman = Peminjaman::with('kelas')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['ditolak', 'selesai'])
            ->latest()
            ->get();

        // Get pending bookings for admin
        $pendingBookings = [];
        if (Auth::user()->role === 'admin') {
            $pendingBookings = Peminjaman::with(['kelas', 'user'])
                ->where('status', 'menunggu')
                ->latest()
                ->get();
        }

        return view('dashboard', compact('kelas','peminjaman', 'pendingBookings', 'riwayatPeminjaman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'     => 'required|exists:kelas,id',
            'nama_matkul'  => 'nullable|string|max:255',
            'kelas_matkul' => 'nullable|string|max:2',
            'kode_matkul'  => 'nullable|string|max:255',
            'keterangan'   => 'required|string',
            'tanggal'      => 'required|date|after_or_equal:today',
            'jam_mulai'    => 'required|date_format:H:i',
            'jam_selesai'  => 'required|date_format:H:i|after:jam_mulai',
        ]);

        Peminjaman::create([
            'user_id'      => Auth::id(),
            'kelas_id'     => $request->kelas_id,
            'nama_matkul'  => $request->nama_matkul,
            'kelas_matkul' => $request->kelas_matkul,
            'kode_matkul'  => $request->kode_matkul,
            'keterangan'   => $request->keterangan,
            'tanggal'      => $request->tanggal,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
            'status'       => 'menunggu',
        ]);

        return redirect()->route('dashboard')->with('success', 'Peminjaman kelas berhasil diajukan dan menunggu persetujuan admin.');
    }

    public function acc($id)
    {
        // Alternative: Check role directly instead of middleware
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status' => 'disetujui']);

        return redirect()->back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function reject($id)
    {
        // Alternative: Check role directly instead of middleware
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status' => 'ditolak']);

        return redirect()->back()->with('success', 'Peminjaman berhasil ditolak.');
    }
}
