<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Tabungan;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function harian(Request $request)
    {
        // Ambil semua tabungan
        $DataTabungan = Tabungan::oldest()->get();
        $data = [
            'judul'        => 'Laporan Harian',
            'DataTabungan' => $DataTabungan,
        ];

        // Jika ada input tanggal, ambil transaksi sesuai tanggal
        if ($request->has('DateR')) {
            $request->validate([
                'DateR' => 'required|date|before_or_equal:today',
            ]);
            $tanggal = $request->DateR;
            $DataTr = $DataTr = Finance::whereDate('created_at', $tanggal)
                ->orderBy('created_at', 'desc')
                ->get();        
            if ($DataTr->isEmpty()) {
                return redirect()->route('report.harian')->with(['error' => 'Data Tidak Ditemukan!']);
            }
            $data['DataTr'] = $DataTr;
            $data['tanggalDipilih'] = $tanggal;

            // Konversi tanggal ke format Indonesia (manual)
            $months = [
                'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
            ];
            $tanggalCarbon = \Carbon\Carbon::parse($tanggal);
            $tanggalFormatted = $tanggalCarbon->format('d') . ' ' . $months[$tanggalCarbon->format('F')] . ' ' . $tanggalCarbon->format('Y');
            $data['tanggalFormatted'] = $tanggalFormatted;
        }
        return view('pages.admin.report_harian', $data);
    }
}
