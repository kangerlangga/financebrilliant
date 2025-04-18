<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Tabungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TabunganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $DataTN = Tabungan::where('category_tabungans', 'Non-Bank')->oldest()->get();
        $DataTB = Tabungan::where('category_tabungans', 'Bank')->oldest()->get();

        $totalSaldoNonBank = 0;
        $totalSaldoBank = 0;
        $saldoPerTabungan = [];

        // Loop untuk hitung saldo tiap tabungan Non-Bank
        foreach ($DataTN as $TN) {
            $saldo = Finance::where('tabungan', $TN->id_tabungans)->latest()->value('saldo_akhir') ?? 0;
            $saldoPerTabungan[$TN->id_tabungans] = $saldo;
            $totalSaldoNonBank += $saldo;
        }

        // Loop untuk hitung saldo tiap tabungan Bank
        foreach ($DataTB as $TB) {
            $saldo = Finance::where('tabungan', $TB->id_tabungans)->latest()->value('saldo_akhir') ?? 0;
            $saldoPerTabungan[$TB->id_tabungans] = $saldo;
            $totalSaldoBank += $saldo;
        }

        // Hitung total saldo semua tabungan
        $totalSaldoSemua = $totalSaldoNonBank + $totalSaldoBank;

        // Hitung persentase tiap tabungan
        $persentasePerTabungan = [];
        foreach ($saldoPerTabungan as $idTabungan => $saldo) {
            $persentasePerTabungan[$idTabungan] = ($totalSaldoSemua > 0) ? ($saldo / $totalSaldoSemua) * 100 : 0;
        }

        return view('pages.admin.tabungan', [
            'judul'  => 'Informasi & Saldo Tabungan',
            'DataTN' => $DataTN,
            'DataTB' => $DataTB,
            'jSk'    => $totalSaldoSemua,
            'jSt'    => $saldoPerTabungan,
            'pT'     => $persentasePerTabungan,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'judul' => 'Tambah Tabungan Baru',
        ];
        return view('pages.admin.tabungan_add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Nama'      => 'required|string|max:255',
            'category'  => 'required|in:Bank,Non-Bank',
            'status'    => 'required|in:Aktif,Nonaktif',
            'Rekening'  => 'nullable|regex:/^[0-9\-]+$/|max:50',
            'Images'    => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        ]);
        
        if ($request->hasFile('Images')) {
            $Images = $request->file('Images');
            $folderPath = public_path('assets/admin/img/Tabungan');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
            $imagePath = time().Str::random(17).'.'.$Images->getClientOriginalExtension();
            $Images->move($folderPath, $imagePath);
        } else {
            $imagePath = null;
        }
    
        Tabungan::create([
            'id_tabungans'        => 'T-'.Str::random(9).'-'.Str::random(6),
            'category_tabungans'  => $request->category,
            'nama_tabungans'      => $request->Nama,
            'rekening_tabungans'  => $request->Rekening,
            'logo_tabungans'      => $imagePath,
            'status_tabungans'    => $request->status,
            'created_by'          => Auth::user()->email,
            'modified_by'         => Auth::user()->email,
        ]);
    
        return redirect()->route('tabungan.add')->with('success', 'Tabungan telah Ditambahkan!');
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
    public function edit(string $id)
    {
        $data = [
            'judul' => 'Edit Tabungan',
            'EditTabungan' => Tabungan::findOrFail($id),
        ];
        return view('pages.admin.tabungan_edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'Nama'      => 'required|string|max:255',
            'status'    => 'required|in:Aktif,Nonaktif',
            'Rekening'  => 'nullable|regex:/^[0-9\-]+$/|max:50',
            'Images'    => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        ]);

        $tabungan = Tabungan::findOrFail($id);

        if ($request->hasFile('Images')) {
            $imagePath = public_path('assets/admin/img/Tabungan/' . $tabungan->logo_tabungans);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $image = $request->file('Images');
            $imageName = time() . Str::random(17) . '.' . $image->getClientOriginalExtension();
            $image->move('assets/admin/img/Tabungan', $imageName);
        } else {
            $imageName = $tabungan->logo_tabungans;
        }

        $tabungan->update([
            'nama_tabungans'        => $request->Nama,
            'rekening_tabungans'    => $request->Rekening,
            'logo_tabungans'        => $imageName,
            'status_tabungans'      => $request->status,
            'modified_by'           => Auth::user()->email,
        ]);

        return redirect()->route('tabungan.data')->with(['success' => 'Tabungan telah Diperbarui!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tabungan = Tabungan::findOrFail($id);
        $saldo = Finance::where('tabungan', $tabungan->id_tabungans)->latest()->value('saldo_akhir') ?? 0;

        if ($tabungan->status_tabungans == 'Nonaktif' && $saldo == 0) {
            $imagePath = public_path('assets/admin/img/Tabungan/' . $tabungan->logo_tabungans);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $tabungan->delete();
            return redirect()->route('tabungan.data')->with(['success' => 'Tabungan telah Dihapus!']);
        }elseif ($saldo != 0) {
            return redirect()->route('tabungan.data')->with(['error' => 'Tabungan masih terdapat Saldo!']);
        }elseif ($tabungan->status_tabungans != 'Nonaktif') {
            return redirect()->route('tabungan.data')->with(['error' => 'Tabungan masih berstatus Aktif!']);
        }
    }
}
