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
            $tanggalCarbon = Carbon::parse($tanggal);

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

    public function bulanan(Request $request)
    {
        $DataTabungan = Tabungan::oldest()->get();
        $data = [
            'judul'        => 'Laporan Bulanan',
            'DataTabungan' => $DataTabungan,
        ];

        if ($request->has('DateR')) {
            $request->validate([
                'DateR' => 'required|date|before_or_equal:today',
            ]);

            $tanggal = $request->DateR;
            $tanggalCarbon = Carbon::parse($tanggal)->startOfMonth();

            $bulan = $tanggalCarbon->format('m');
            $tahun = $tanggalCarbon->format('Y');

            // Manual format bulan Indonesia
            $bulanIndonesia = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
            ];

            $data['tanggalDipilih'] = $tanggal;
            $data['tanggalFormatted'] = $bulanIndonesia[$bulan] . ' ' . $tahun;

            // Ambil transaksi dalam bulan yang dipilih
            $DataTr = Finance::with('tabunganRelasi')
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($DataTr->isEmpty()) {
                return redirect()->route('report.bulanan')->with(['error' => 'Data Tidak Ditemukan!']);
            }

            $data['DataTr'] = $DataTr;
            $data['OutM'] = $DataTr->sum('out_money');
            $data['InM'] = $DataTr->sum('in_money');

            // ⏳ 6 BULAN TERAKHIR
            $range6 = collect();
            for ($i = 5; $i >= 0; $i--) {
                $range6->push($tanggalCarbon->copy()->subMonths($i));
            }

            // Ambil total pemasukan & pengeluaran per bulan
            $masuk = Finance::select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
                    DB::raw("SUM(in_money) as total_masuk")
                )
                ->whereBetween('created_at', [
                    $range6->first()->copy()->startOfMonth(),
                    $range6->last()->copy()->endOfMonth()
                ])
                ->groupBy('bulan')
                ->pluck('total_masuk', 'bulan');

            $keluar = Finance::select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
                    DB::raw("SUM(out_money) as total_keluar")
                )
                ->whereBetween('created_at', [
                    $range6->first()->copy()->startOfMonth(),
                    $range6->last()->copy()->endOfMonth()
                ])
                ->groupBy('bulan')
                ->pluck('total_keluar', 'bulan');

            // Saldo total tiap akhir bulan
            $tabungans = Tabungan::all();
            $saldoPerBulan = [];

            foreach ($range6 as $bulanItem) {
                $bulanStr = $bulanItem->format('Y-m');
                $totalSaldo = 0;
                foreach ($tabungans as $tabungan) {
                    $saldo = Finance::where('tabungan', $tabungan->id_tabungans)
                        ->whereYear('created_at', $bulanItem->format('Y'))
                        ->whereMonth('created_at', $bulanItem->format('m'))
                        ->orderBy('created_at', 'desc')
                        ->value('saldo_akhir') ?? 0;
                    $totalSaldo += $saldo;
                }
                $saldoPerBulan[$bulanStr] = $totalSaldo;
            }

            // 🔖 Siapkan label dan dataset grafik
            $labels6 = [];
            $dataKeluar = [];
            $dataMasuk = [];
            $dataSaldo = [];

            foreach ($range6 as $bulanItem) {
                $bln = $bulanItem->format('m');
                $thn = $bulanItem->format('Y');
                $key = $bulanItem->format('Y-m');

                $labels6[] = $bulanIndonesia[$bln] . ' ' . $thn;
                $dataKeluar[] = $keluar[$key] ?? 0;
                $dataMasuk[] = $masuk[$key] ?? 0;
                $dataSaldo[] = $saldoPerBulan[$key] ?? 0;
            }

            $data['labels6']     = $labels6;
            $data['dataKeluar']  = $dataKeluar;
            $data['dataMasuk']   = $dataMasuk;
            $data['dataSaldo']   = $dataSaldo;
        }
        return view('pages.admin.report_bulanan', $data);
    }

    public function tahunan(Request $request)
    {
        $DataTabungan = Tabungan::oldest()->get();
        $data = [
            'judul' => 'Laporan Tahunan',
            'DataTabungan' => $DataTabungan,
        ];

        if ($request->has('DateR')) {
            $request->validate([
                'DateR' => 'required|numeric|min:2000|max:' . now()->year,
            ]);

            $tahun = (int) $request->DateR;
            $data['tanggalDipilih'] = $tahun;

            // Ambil transaksi di tahun yang dipilih
            $DataTr = Finance::with('tabunganRelasi')
                ->whereYear('created_at', $tahun)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($DataTr->isEmpty()) {
                return redirect()->route('report.tahunan')->with(['error' => 'Data Tidak Ditemukan!']);
            }

            $data['DataTr'] = $DataTr;
            $data['OutM'] = $DataTr->sum('out_money');
            $data['InM'] = $DataTr->sum('in_money');

            // Ambil 5 tahun ke belakang termasuk tahun dipilih
            $range5 = collect();
            for ($i = 4; $i >= 0; $i--) {
                $range5->push($tahun - $i);
            }

            // Data Pengeluaran
            $keluar = Finance::select(
                    DB::raw("YEAR(created_at) as tahun"),
                    DB::raw("SUM(out_money) as total_keluar")
                )
                ->whereIn(DB::raw("YEAR(created_at)"), $range5)
                ->groupBy('tahun')
                ->pluck('total_keluar', 'tahun');

            // Data Pemasukan
            $masuk = Finance::select(
                    DB::raw("YEAR(created_at) as tahun"),
                    DB::raw("SUM(in_money) as total_masuk")
                )
                ->whereIn(DB::raw("YEAR(created_at)"), $range5)
                ->groupBy('tahun')
                ->pluck('total_masuk', 'tahun');

            // Data Saldo akhir
            $tabungans = Tabungan::all();
            $saldoPerTahun = [];

            foreach ($range5 as $thn) {
                $totalSaldo = 0;
                foreach ($tabungans as $tabungan) {
                    $saldo = Finance::where('tabungan', $tabungan->id_tabungans)
                        ->whereYear('created_at', '<=', $thn)
                        ->orderBy('created_at', 'desc')
                        ->value('saldo_akhir') ?? 0;
                    $totalSaldo += $saldo;
                }
                $saldoPerTahun[$thn] = $totalSaldo;
            }

            // Generate data grafik
            $labels5 = [];
            $dataKeluar = [];
            $dataMasuk = [];
            $dataSaldo = [];

            foreach ($range5 as $thn) {
                $labels5[] = (string) $thn;
                $dataKeluar[] = $keluar[$thn] ?? 0;
                $dataMasuk[] = $masuk[$thn] ?? 0;
                $dataSaldo[] = $saldoPerTahun[$thn] ?? 0;
            }

            $data['labels5']     = $labels5;
            $data['dataKeluar']  = $dataKeluar;
            $data['dataMasuk']   = $dataMasuk;
            $data['dataSaldo']   = $dataSaldo;
        }
        return view('pages.admin.report_tahunan', $data);
    }
}