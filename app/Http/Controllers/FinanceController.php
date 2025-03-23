<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function kas_data()
    {
        $data = [
            'judul' => 'Pencatatan Kas (Tanpa Rekening)',
            'DataTr' => Finance::where('tabungan', 'Kas')->latest()->get(),
        ];
        return view('pages.admin.tr_data', $data);
    }

    public function bca_data()
    {
        $data = [
            'judul' => 'Pencatatan Rekening BCA',
            'DataTr' => Finance::where('tabungan', 'BCA')->latest()->get(),
        ];
        return view('pages.admin.tr_data', $data);
    }

    public function bri_data()
    {
        $data = [
            'judul' => 'Pencatatan Rekening BRI',
            'DataTr' => Finance::where('tabungan', 'BRI')->latest()->get(),
        ];
        return view('pages.admin.tr_data', $data);
    }

    public function bni_data()
    {
        $data = [
            'judul' => 'Pencatatan Rekening BNI',
            'DataTr' => Finance::where('tabungan', 'BNI')->latest()->get(),
        ];
        return view('pages.admin.tr_data', $data);
    }

    public function mandiri_data()
    {
        $data = [
            'judul' => 'Pencatatan Rekening Mandiri',
            'DataTr' => Finance::where('tabungan', 'Mandiri')->latest()->get(),
        ];
        return view('pages.admin.tr_data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'judul' => 'Catat Transaksi Baru',
        ];
        return view('pages.admin.tr_add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Keterangan'=> 'required|max:255',
            'Debit'     => 'required|numeric|min:0',
            'Kredit'    => 'required|numeric|min:0',
        ]);

        $latestFinance = Finance::where('tabungan', $request->Tabungan)->latest()->first();
        $saldoAwal = $latestFinance ? $latestFinance->saldo_akhir : 0;
        $saldoAkhir = ($saldoAwal - $request->Debit) + $request->Kredit;

        Finance::create([
            'id_finances'   => 'FT-'.Str::uuid(),
            'tabungan'      => $request->Tabungan,
            'saldo_awal'    => $saldoAwal,
            'out_debit'     => $request->Debit,
            'in_kredit'     => $request->Kredit,
            'saldo_akhir'   => $saldoAkhir,
            'noted'         => $request->Keterangan,
            'created_by'    => Auth::user()->email,
            'modified_by'   => Auth::user()->email,
        ]);

        return redirect()->route('trans.add')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Finance $finance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Finance $finance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Finance $finance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Finance $finance)
    {
        //
    }

    public function saldo()
    {
        // Ambil saldo terakhir dari masing-masing tabungan
        $saldoKas = Finance::where('tabungan', 'Kas')->latest()->value('saldo_akhir') ?? 0;
        $saldoBCA = Finance::where('tabungan', 'BCA')->latest()->value('saldo_akhir') ?? 0;
        $saldoBRI = Finance::where('tabungan', 'BRI')->latest()->value('saldo_akhir') ?? 0;
        $saldoBNI = Finance::where('tabungan', 'BNI')->latest()->value('saldo_akhir') ?? 0;
        $saldoMandiri = Finance::where('tabungan', 'Mandiri')->latest()->value('saldo_akhir') ?? 0;
        
        // Total saldo semua tabungan
        $saldoAll = $saldoKas + $saldoBCA + $saldoBRI + $saldoBNI + $saldoMandiri;

        $data = [
            'judul' => 'Saldo Tabungan',
            'jSKas' => $saldoKas,
            'jSBCA' => $saldoBCA,
            'jSBRI' => $saldoBRI,
            'jSBNI' => $saldoBNI,
            'jSMan' => $saldoMandiri,
            'jSk'   => $saldoAll,
        ];
        return view('pages.admin.saldo', $data);
    }
}
