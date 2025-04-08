<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Program;
use App\Models\Tabungan;
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
        $ValueBlueLeft = 0; $ValueRedLeft = 0; $ValueGreenRight = 0;
        $now = time(); $fiveMinutesAgo = $now - 300;

        if (Auth::user()->level == 'Finance' || Auth::user()->level == 'Super-User') {
            $DataTN = Tabungan::where('category_tabungans', 'Non-Bank')->oldest()->get();
            $DataTB = Tabungan::where('category_tabungans', 'Bank')->oldest()->get();

            $totalSaldoNonBank = 0;
            $totalSaldoBank = 0;

            // Loop untuk hitung saldo tiap tabungan Non-Bank
            foreach ($DataTN as $TN) {
                $saldo = Finance::where('tabungan', $TN->id_tabungans)->latest()->value('saldo_akhir') ?? 0;
                $totalSaldoNonBank += $saldo;
            }

            // Loop untuk hitung saldo tiap tabungan Bank
            foreach ($DataTB as $TB) {
                $saldo = Finance::where('tabungan', $TB->id_tabungans)->latest()->value('saldo_akhir') ?? 0;
                $totalSaldoBank += $saldo;
            }

            // Hitung total saldo semua tabungan
            $totalSaldoSemua = $totalSaldoNonBank + $totalSaldoBank;
            $ValueBlueLeft = $totalSaldoSemua;

            // Hitung total pengeluaran bulan ini
            $ValueRedLeft = Finance::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))
            ->sum('out_money');

            // Hitung total pemasukan bulan ini
            $ValueGreenRight = Finance::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))
            ->sum('in_money');
        }

        $data = [
            'judul' => 'Dashboard',
            'vBl'   => $ValueBlueLeft,
            'vRl'   => $ValueRedLeft,
            'vGr'   => $ValueGreenRight,
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
