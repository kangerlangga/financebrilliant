<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Tabungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua tabungan
        $DataTabungan = Tabungan::oldest()->get();

        // Ambil semua transaksi, diurutkan dari terbaru ke terlama
        $DataTr = Finance::latest()->get();

        // Kirim data ke view
        $data = [
            'judul'        => 'Pencatatan Transaksi',
            'DataTabungan' => $DataTabungan,
            'DataTr'       => $DataTr,
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
            'DataT' => Tabungan::oldest()->get(),
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
        $saldoAkhir = ($saldoAwal - $request->Kredit) + $request->Debit;

        Finance::create([
            'id_finances'   => 'FT-'.Str::uuid(),
            'tabungan'      => $request->Tabungan,
            'saldo_awal'    => $saldoAwal,
            'in_money'      => $request->Debit,
            'out_money'     => $request->Kredit,
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
}
