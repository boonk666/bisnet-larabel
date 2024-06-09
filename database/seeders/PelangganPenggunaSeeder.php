<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer as Pelanggan;
use App\Models\User;

class PelangganPenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_pelanggan = Pelanggan::whereNull('id_pengguna')->get();

        $data_pelanggan->each(function ($pelanggan) {
            $user = User::create([
                'nama' => $pelanggan->nama,
                'username' => $pelanggan->nama,
                'password' => $pelanggan->no_telp
            ]);
    
            $pelanggan->update([
                'id_pengguna' => $user->id_pengguna
            ]);  
        });
    }
}
