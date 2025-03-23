<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $now = time();
        $fiveMinutesAgo = $now - 300;

        // Ambil saldo terakhir dari masing-masing tabungan
        $saldoKas = Finance::where('tabungan', 'Kas')->latest()->value('saldo_akhir') ?? 0;
        $saldoBCA = Finance::where('tabungan', 'BCA')->latest()->value('saldo_akhir') ?? 0;
        $saldoBRI = Finance::where('tabungan', 'BRI')->latest()->value('saldo_akhir') ?? 0;
        $saldoBNI = Finance::where('tabungan', 'BNI')->latest()->value('saldo_akhir') ?? 0;
        $saldoMandiri = Finance::where('tabungan', 'Mandiri')->latest()->value('saldo_akhir') ?? 0;
        
        // Total saldo semua tabungan
        $saldoAll = $saldoKas + $saldoBCA + $saldoBRI + $saldoBNI + $saldoMandiri;

        // Hitung total pengeluaran bulan ini
        $OutMonth = Finance::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))
        ->sum('out_debit');

        // Hitung total pemasukan bulan ini
        $InMonth = Finance::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))
        ->sum('in_kredit');

        $data = [
            'judul' => 'Dashboard',
            'jSk'   => $saldoAll,
            'jOm'   => $OutMonth,
            'jIm'   => $InMonth,
            'cVO'   => DB::table('sessions')->where('last_activity', '>=', $fiveMinutesAgo)->count(),
        ];
        return view('pages.admin.dashboard', $data);
    }

    public function editProf()
    {
        $data = [
            'judul' => 'Edit Profil',
            // 'cMC' => Message::where('status_messages', 'Unread')->count(),
        ];
        return view('pages.admin.profile_edit', $data);
    }

    public function updateProf(Request $request)
    {
        $passProf = User::findOrFail(Auth::user()->id);

        if (password_verify($request->password, $passProf->password)) {
            // validate form
            $request->validate([
                'Nama'      => 'required|max:45',
                'Address'   => 'required|max:255',
                'Position'  => 'required|max:255',
                'Phone'     => 'required|numeric|max_digits:20',
            ]);

            //get by ID
            $profil = User::findOrFail(Auth::user()->id);

            //update
            $profil->update([
                'name'          => $request->Nama,
                'alamat'        => $request->Address,
                'jabatan'       => $request->Position,
                'telp'          => $request->Phone,
                'modified_by'   => Auth::user()->email,
            ]);

            //redirect to index
            return redirect()->route('admin.dash')->with(['successprof' => 'Akun Anda telah diperbarui!']);
        }else{
            return redirect()->route('prof.edit')->with(['passerror' => 'Password Anda saat ini salah!']);
        }
    }

    public function editPass()
    {
        $data = [
            'judul' => 'Ganti Password',
            // 'cMC' => Message::where('status_messages', 'Unread')->count(),
        ];
        return view('pages.admin.profile_editpass', $data);
    }

    public function updatePass(Request $request)
    {
        $passEdit = User::findOrFail(Auth::user()->id);

        if (password_verify($request->oldPass, $passEdit->password)) {
            // validate form
            $request->validate([
                'newPass' => ['required', Password::defaults()],
                'password_confirmation'  => 'required|same:newPass',
            ]);

            //get by ID
            $profil = User::findOrFail(Auth::user()->id);

            //update
            $profil->update([
                'password'    => Hash::make($request->newPass),
                'modified_by' => Auth::user()->email,
            ]);

            //redirect to index
            return redirect()->route('prof.edit.pass')->with(['success' => 'Password Anda telah diperbarui!']);
        }else{
            return redirect()->route('prof.edit.pass')->with(['error' => 'Password Anda saat ini salah!']);
        }
    }
}
