<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defPass = 'Admin.BEC42';
        $sandi = Hash::make($defPass);

        User::create([
            'id_akun'       => 'Akun' . Str::random(33),
            'name'          => 'Super Admin',
            'email'         => 'super@gmail.com',
            'password'      => $sandi,
            'alamat'        => 'Alamat',
            'jabatan'       => 'Administrator',
            'telp'          => '081234567890',
            'level'         => 'Super-User',
            'created_by'    => '(Seeder)',
            'modified_by'   => '(Seeder)',
        ]);
    }
}
