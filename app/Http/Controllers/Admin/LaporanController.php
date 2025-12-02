<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PeminjamanExport;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status', 'all');

        $query = Peminjaman::with(['user', 'buku'])
            ->whereDate('tanggal_peminjaman', '>=', $startDate)
            ->whereDate('tanggal_peminjaman', '<=', $endDate);

        if ($status !== 'all') {
            $query->where('status_peminjaman', $status);
        }

        $peminjaman = $query->latest()->get();

        return view('admin.laporan.index', compact('peminjaman', 'startDate', 'endDate', 'status'));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        $query = Peminjaman::with(['user', 'buku'])
            ->whereDate('tanggal_peminjaman', '>=', $startDate)
            ->whereDate('tanggal_peminjaman', '<=', $endDate);

        if ($status !== 'all') {
            $query->where('status_peminjaman', $status);
        }

        $data = $query->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('data', 'startDate', 'endDate'));
        return $pdf->download('laporan-peminjaman-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        return Excel::download(new PeminjamanExport($startDate, $endDate, $status), 'laporan-peminjaman-' . Carbon::now()->format('Y-m-d') . '.xlsx');
    }
}
