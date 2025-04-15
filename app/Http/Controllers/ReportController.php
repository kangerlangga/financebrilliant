<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Tabungan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $tanggalCarbon = \Carbon\Carbon::parse($tanggal);

            $DataTr = Finance::with('tabunganRelasi')->whereDate('created_at', $tanggal)
                ->orderBy('created_at', 'desc')->get();

            if ($DataTr->isEmpty()) {
                return redirect()->route('report.harian')->with(['error' => 'Data Tidak Ditemukan!']);
            }

            $data['DataTr'] = $DataTr;
            $data['tanggalDipilih'] = $tanggal;
            $data['OutM'] = $DataTr->sum('out_money');
            $data['InM'] = $DataTr->sum('in_money');

            // Format tanggal Indonesia
            $months = [
                'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
            ];
            $tanggalFormatted = $tanggalCarbon->format('d') . ' ' . $months[$tanggalCarbon->format('F')] . ' ' . $tanggalCarbon->format('Y');
            $data['tanggalFormatted'] = $tanggalFormatted;

            // Ambil 7 hari terakhir dari tanggal input
            $range7 = collect();
            for ($i = 6; $i >= 0; $i--) {
                $range7->push($tanggalCarbon->copy()->subDays($i));
            }

            // Ambil data pengeluaran dan pemasukan
            $keluar = Finance::select(
                    DB::raw("DATE(created_at) as tanggal"),
                    DB::raw("SUM(out_money) as total_keluar")
                )
                ->whereBetween('created_at', [
                    $tanggalCarbon->copy()->subDays(6)->startOfDay(),
                    $tanggalCarbon->endOfDay()
                ])
                ->groupBy('tanggal')
                ->pluck('total_keluar', 'tanggal');

            $masuk = Finance::select(
                    DB::raw("DATE(created_at) as tanggal"),
                    DB::raw("SUM(in_money) as total_masuk")
                )
                ->whereBetween('created_at', [
                    $tanggalCarbon->copy()->subDays(6)->startOfDay(),
                    $tanggalCarbon->endOfDay()
                ])
                ->groupBy('tanggal')
                ->pluck('total_masuk', 'tanggal');

            // Data saldo: jumlahkan saldo terakhir dari tiap tabungan di setiap tanggal
            $tabungans = Tabungan::all();
            $saldoPerHari = [];

            foreach ($range7 as $tgl) {
                $tglStr = $tgl->format('Y-m-d');
                $totalSaldo = 0;
                foreach ($tabungans as $tabungan) {
                    $saldo = Finance::where('tabungan', $tabungan->id_tabungans)
                        ->whereDate('created_at', '<=', $tglStr)
                        ->orderBy('created_at', 'desc')
                        ->value('saldo_akhir') ?? 0;
                    $totalSaldo += $saldo;
                }
                $saldoPerHari[$tglStr] = $totalSaldo;
            }

            // Generate label dan data grafik
            $labels7 = [];
            $dataKeluar = [];
            $dataMasuk = [];
            $dataSaldo = [];

            foreach ($range7 as $tgl) {
                $tglStr = $tgl->format('Y-m-d');
                $labels7[] = $tgl->format('d M y');
                $dataKeluar[] = $keluar[$tglStr] ?? 0;
                $dataMasuk[] = $masuk[$tglStr] ?? 0;
                $dataSaldo[] = $saldoPerHari[$tglStr] ?? 0;
            }
            
            $data['labels7']     = $labels7;
            $data['dataKeluar']  = $dataKeluar;
            $data['dataMasuk']   = $dataMasuk;
            $data['dataSaldo']   = $dataSaldo;
        }
        return view('pages.admin.report_harian', $data);
    }
}