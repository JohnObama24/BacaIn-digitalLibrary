<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use Carbon\Carbon;

class AutoReturnEbooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebooks:auto-return';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically return overdue e-books';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!config('borrowing.auto_return_ebooks')) {
            $this->info('Auto-return e-books is disabled in config.');
            return 0;
        }

        // Find all overdue e-book loans
        $overdueLoans = Peminjaman::with('buku')
            ->where('status_peminjaman', 'dipinjam')
            ->where('tanggal_pengembalian', '<', Carbon::now())
            ->get()
            ->filter(function ($peminjaman) {
                return $peminjaman->isEbook();
            });

        $count = 0;
        foreach ($overdueLoans as $loan) {
            $loan->update([
                'status_peminjaman' => 'dikembalikan',
                'tanggal_kembali_actual' => Carbon::now(),
            ]);
            $count++;
        }

        $this->info("Auto-returned {$count} overdue e-book(s).");
        return 0;
    }
}
