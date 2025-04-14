<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Program;
use App\Models\Tabungan;
use App\Models\User;
use Carbon\Carbon;
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
            $ValueRedLeft = Finance::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))
            ->sum('out_money');

            // Hitung total pemasukan bulan ini
            $ValueGreenRight = Finance::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))
            ->sum('in_money');

            // 6 bulan terakhir untuk pemasukan dan pengeluaran
            $periode6 = collect();
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $periode6->push($date->format('Y-m'));
            }

            // 12 bulan terakhir untuk saldo
            $periode12 = collect();
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $periode12->push($date->format('Y-m'));
            }

            // Ambil pemasukan & pengeluaran
            $keluar = Finance::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as periode"),
                DB::raw('SUM(out_money) as total_keluar')
            )
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('periode')
            ->pluck('total_keluar', 'periode');

            $masuk = Finance::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as periode"),
                DB::raw('SUM(in_money) as total_masuk')
            )
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('periode')
            ->pluck('total_masuk', 'periode');

            $saldo = [];
            foreach ($periode12 as $periode) {
                $totalSaldoBulan = 0;
                foreach ($DataTN as $TN) {
                    $saldoTabungan = Finance::where('tabungan', $TN->id_tabungans)
                        ->whereYear('created_at', substr($periode, 0, 4))
                        ->whereMonth('created_at', substr($periode, 5, 2))
                        ->latest()
                        ->value('saldo_akhir') ?? 0;
                    $totalSaldoBulan += $saldoTabungan;
                }
                foreach ($DataTB as $TB) {
                    $saldoTabungan = Finance::where('tabungan', $TB->id_tabungans)
                        ->whereYear('created_at', substr($periode, 0, 4))
                        ->whereMonth('created_at', substr($periode, 5, 2))
                        ->latest()
                        ->value('saldo_akhir') ?? 0;
                    $totalSaldoBulan += $saldoTabungan;
                }
                $saldo[$periode] = $totalSaldoBulan;
            }

            // Format label bulan
            $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $labels6 = $periode6->map(function ($p) use ($monthNames) {
                $date = Carbon::createFromFormat('Y-m', $p);
                return $monthNames[$date->month - 1] . ' ' . $date->format('y');
            });
            
            $labels12 = $periode12->map(function ($p) use ($monthNames) {
                $date = Carbon::createFromFormat('Y-m', $p);
                return $monthNames[$date->month - 1] . ' ' . $date->format('y');
            });            

            $data['labels6']     = $labels6;
            $data['labels12']    = $labels12;
            $data['dataKeluar']  = $periode6->map(fn($p) => $keluar[$p] ?? 0);
            $data['dataMasuk']   = $periode6->map(fn($p) => $masuk[$p] ?? 0);
            $data['dataSaldo']   = $periode12->map(fn($p) => $saldo[$p] ?? 0);
        }

        $data['judul'] = 'Dashboard';
        $data['vBl']   = $ValueBlueLeft;
        $data['vRl']   = $ValueRedLeft;
        $data['vGr']   = $ValueGreenRight;
        $data['cVO']   = DB::table('sessions')->where('last_activity', '>=', $fiveMinutesAgo)->count();

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
