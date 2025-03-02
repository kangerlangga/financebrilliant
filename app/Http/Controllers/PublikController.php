<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublikController extends Controller
{
    //Fungsi untuk halaman coming
    public function coming()
    {
        return view('pages.public.coming', [
            'judul' => 'Coming Soon',
        ]);
    }
}
