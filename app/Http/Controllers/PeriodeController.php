<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PeriodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'judul' => 'Manajemen Periode',
            'DataP' => Periode::latest()->get(),
        ];
        return view('pages.admin.periode', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'judul' => 'Tambah Periode',
        ];
        return view('pages.admin.periode_add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'DateP' => 'required|date|after_or_equal:today',
        ]);

        $dateP = date('Y-m-d', strtotime($request->DateP));

        Periode::create([
            'id_periodes'       => 'Periode'.Str::random(33),
            'category_periodes' => $request->category,
            'date_periodes'     => $dateP,
            'status_periodes'   => $request->status,
            'created_by'        => Auth::user()->email,
            'modified_by'       => Auth::user()->email
        ]);

        return redirect()->route('periode.add')->with(['success' => 'Periode telah Ditambahkan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Periode $periode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Periode $periode)
    {
        //
    }

    public function aktif(string $id)
    {
        //get by ID
        $periode = Periode::findOrFail($id);

        //aktifkan
        $periode->update([
            'status_periodes'   => 'Aktif',
            'modified_by'       => Auth::user()->email,
        ]);

        //redirect
        return redirect()->route('periode.data')->with(['success' => 'Periode telah Di Aktifkan!']);
    }

    public function nonaktif(string $id)
    {
        //get by ID
        $periode = Periode::findOrFail($id);

        //nonaktifkan
        $periode->update([
            'status_periodes'   => 'Nonaktif',
            'modified_by'       => Auth::user()->email,
        ]);

        //redirect
        return redirect()->route('periode.data')->with(['success' => 'Periode telah Di Nonaktifkan!']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Periode $periode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //get by ID
        $periode = Periode::findOrFail($id);

        //delete
        $periode->delete();

        //redirect
        return redirect()->route('periode.data')->with(['success' => 'Periode telah Dihapus!']);
    }
}
