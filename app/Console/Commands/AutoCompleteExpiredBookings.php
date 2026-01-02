<?php

namespace App\Console\Commands;

use App\Models\Peminjaman;
use Illuminate\Console\Command;

class AutoCompleteExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-complete-expired-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically mark expired approved bookings as completed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDate = now()->toDateString();
        $currentTime = now()->toTimeString();

        // Find approved bookings that have expired
        $expiredBookings = Peminjaman::where('status', 'disetujui')
            ->where(function($query) use ($currentDate, $currentTime) {
                // Bookings that are:
                // 1. Past dates, OR
                // 2. Today but end time has passed
                $query->where('tanggal', '<', $currentDate)
                      ->orWhere(function($q) use ($currentDate, $currentTime) {
                          $q->where('tanggal', '=', $currentDate)
                            ->where('jam_selesai', '<=', $currentTime);
                      });
            })
            ->get();

        $count = $expiredBookings->count();

        if ($count > 0) {
            foreach ($expiredBookings as $booking) {
                $booking->update(['status' => 'selesai']);
            }

            $this->info("Marked {$count} expired bookings as completed.");
        } else {
            $this->info('No expired bookings found.');
        }

        return 0;
    }
}
