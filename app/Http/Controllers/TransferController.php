<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Tabungan;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'judul'  => 'Pencatatan Perpindahan Dana',
            'DataTr' => Transfer::latest()->get(),
            'DataTl' => Tabungan::pluck('nama_tabungans', 'id_tabungans'),
        ];
        return view('pages.admin.transfer', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'judul' => 'Tambah Perpindahan Dana',
            'DataT' => Tabungan::where('status_tabungans', 'Aktif')->oldest()->get(),
        ];
        return view('pages.admin.transfer_add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Keterangan'     => 'required|max:255',
            'Nominal'        => 'required|numeric|min:1',
            'Admin'          => 'required|numeric|min:0',
            'TabunganAsal'   => 'required|different:TabunganTujuan',
            'TabunganTujuan' => 'required',
        ]);

        // Ambil saldo terakhir dari tabungan asal
        $latestFinance = Finance::where('tabungan', $request->TabunganAsal)->latest()->first();
        $saldoTAwal = $latestFinance ? $latestFinance->saldo_akhir : 0;
        $transfer = $request->Nominal + $request->Admin;

        // Cek apakah saldo cukup
        if ($transfer > $saldoTAwal) {
            return redirect()->route('transfer.add')->with(['error' => 'Saldo Tidak Mencukupi!']);
        }

        // Gunakan database transaction untuk menjaga konsistensi data
        DB::beginTransaction();

        try {
            $updateFinanceOut = $this->updateFinanceOut($request);
            $updateFinanceIn = $this->updateFinanceIn($request);

            if ($updateFinanceOut && $updateFinanceIn) {
                Transfer::create([
                    'id_transfers'      => 'TF-' . Str::uuid(),
                    'tabungan_asal'     => $request->TabunganAsal,
                    'tabungan_tujuan'   => $request->TabunganTujuan,
                    'nominal'           => $request->Nominal,
                    'admin'             => $request->Admin,
                    'noted'             => $request->Keterangan,
                    'created_by'        => Auth::user()->email,
                    'modified_by'       => Auth::user()->email,
                ]);

                DB::commit(); // Simpan semua perubahan jika berhasil
                return redirect()->route('transfer.data')->with(['success' => 'Perpindahan Dana Berhasil!']);
            } else {
                DB::rollBack(); // Batalkan semua perubahan jika ada kegagalan
                return redirect()->route('transfer.add')->with(['error' => 'Gagal Memperbarui Saldo!']);
            }
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan perubahan jika terjadi error
            return redirect()->route('transfer.add')->with(['error' => 'Terjadi Kesalahan Sistem!']);
        }
    }

    public function updateFinanceOut(Request $request)
    {
        $latestFinance = Finance::where('tabungan', $request->TabunganAsal)->latest()->first();
        $saldoAwal = $latestFinance ? $latestFinance->saldo_akhir : 0;
        $transfer = $request->Nominal + $request->Admin;
        $saldoAkhir = $saldoAwal - $transfer;

        // Pastikan saldo cukup sebelum membuat transaksi
        if ($transfer > $saldoAwal) {
            return false; // Gagal jika saldo tidak cukup
        }

        // Buat transaksi keluar
        Finance::create([
            'id_finances'   => 'FT-' . Str::uuid(),
            'tabungan'      => $request->TabunganAsal,
            'saldo_awal'    => $saldoAwal,
            'out_money'     => $transfer,
            'in_money'      => 0,
            'saldo_akhir'   => $saldoAkhir,
            'noted'         => Tabungan::where('id_tabungans', $request->TabunganAsal)->value('nama_tabungans') 
                                . ' -> ' . 
                                Tabungan::where('id_tabungans', $request->TabunganTujuan)->value('nama_tabungans') 
                                . ' (Uang Keluar) [' . $request->Keterangan . ']',
            'created_by'    => Auth::user()->email,
            'modified_by'   => Auth::user()->email,
        ]);

        return true;
    }

    public function updateFinanceIn(Request $request)
    {
        $latestFinance = Finance::where('tabungan', $request->TabunganTujuan)->latest()->first();
        $saldoAwal = $latestFinance ? $latestFinance->saldo_akhir : 0;
        $saldoAkhir = $saldoAwal + $request->Nominal;

        // Buat transaksi masuk
        Finance::create([
            'id_finances'   => 'FT-' . Str::uuid(),
            'tabungan'      => $request->TabunganTujuan,
            'saldo_awal'    => $saldoAwal,
            'out_money'     => 0,
            'in_money'      => $request->Nominal,
            'saldo_akhir'   => $saldoAkhir,
            'noted'         => Tabungan::where('id_tabungans', $request->TabunganAsal)->value('nama_tabungans') 
                                . ' -> ' . 
                                Tabungan::where('id_tabungans', $request->TabunganTujuan)->value('nama_tabungans') 
                                . ' (Uang Masuk) [' . $request->Keterangan . ']',
            'created_by'    => Auth::user()->email,
            'modified_by'   => Auth::user()->email,
        ]);

        return true;
    }

    /**
     * Display the specified resource.
     */
    public function show(Transfer $transfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transfer $transfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transfer $transfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transfer $transfer)
    {
        //
    }
}
