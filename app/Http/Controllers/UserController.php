<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'judul' => 'Manajemen Akun',
            'DataU' => User::latest()->get(),
        ];
        return view('pages.admin.user', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'judul' => 'Buat Akun Baru',
            // 'cMC' => Message::where('status_messages', 'Unread')->count(),
        ];
        return view('pages.admin.user_add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'Nama'      => 'required|max:45',
            'Email'     => 'required|email|unique:users,email|max:255',
            'Address'   => 'required|max:255',
            'Position'  => 'required|max:255',
            'Phone'     => 'required|numeric|max_digits:20',
        ]);

        $defPass = 'Admin.BEC42';
        $sandi = Hash::make($defPass);

        User::create([
            'id_akun'       => 'Akun'.Str::random(33),
            'name'          => $request->Nama,
            'email'         => $request->Email,
            'password'      => $sandi,
            'alamat'        => $request->Address,
            'jabatan'       => $request->Position,
            'telp'          => $request->Phone,
            'level'         => $request->Level,
            'created_by'    => Auth::user()->email,
            'modified_by'   => Auth::user()->email,
        ]);

        //redirect to index
        return redirect()->route('user.add')->with(['success' => 'Akun telah Ditambahkan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_akun)
    {
        $getID = User::where('id_akun', $id_akun)->first();
        $akun = User::findOrFail($getID->id);
        if ($akun->id_akun == Auth::user()->id_akun) {
            //redirect to index
            return redirect()->route('prof.edit');
        }else{
            $data = [
                'judul' => 'Edit Informasi Akun',
                'EditUser' => $akun,

                // 'cMC' => Message::where('status_messages', 'Unread')->count(),
            ];
            return view('pages.admin.user_edit', $data);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_akun)
    {
        $request->validate([
            'Nama'      => 'required|max:45',
            'Address'   => 'required|max:255',
            'Position'  => 'required|max:255',
            'Phone'     => 'required|numeric|max_digits:20',
        ]);

        $getID = User::where('id_akun', $id_akun)->first();
        $akun = User::findOrFail($getID->id);

        //update
        $akun->update([
            'name'          => $request->Nama,
            'alamat'        => $request->Address,
            'jabatan'       => $request->Position,
            'telp'          => $request->Phone,
            'level'         => $request->Level,
            'modified_by'   => Auth::user()->email,
        ]);

        //redirect to index
        return redirect()->route('user.data')->with(['success' => 'Akun telah Diperbarui!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_akun)
    {
        $getID = User::where('id_akun', $id_akun)->first();
        $akun = User::findOrFail($getID->id);
        if ($akun->id_akun == Auth::user()->id_akun) {
            return redirect()->route('user.data')->with(['error' => 'Gagal Menghapus Akun!']);
        }else{
            $akun->delete();
            return redirect()->route('user.data')->with(['success' => 'Akun telah Dihapus!']);
        }
    }

    public function resetPass(string $id_akun)
    {
        $getID = User::where('id_akun', $id_akun)->first();
        $akun = User::findOrFail($getID->id);
        if ($akun->id_akun == Auth::user()->id_akun) {
            return redirect()->route('user.data')->with(['error' => 'Gagal Mereset Password!']);
        }else{
            $defPass = 'Admin.BEC42';
            $sandi = password_hash($defPass, PASSWORD_DEFAULT);
            $akun->update([
                'password'    => $sandi,
                'modified_by' => Auth::user()->email,
            ]);
            return redirect()->route('user.data')->with(['success' => 'Password telah Direset!']);
        }
    }
}
