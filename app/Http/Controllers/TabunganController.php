<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Tabungan;
use Illuminate\Http\Request;

class TabunganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
            'judul' => 'Informasi & Saldo Tabungan',
            'jSKas' => $saldoKas,
            'jSBCA' => $saldoBCA,
            'jSBRI' => $saldoBRI,
            'jSBNI' => $saldoBNI,
            'jSMan' => $saldoMandiri,
            'jSk'   => $saldoAll,
        ];
        return view('pages.admin.tabungan', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Tabungan $tabungan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tabungan $tabungan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tabungan $tabungan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tabungan $tabungan)
    {
        //
    }
}
