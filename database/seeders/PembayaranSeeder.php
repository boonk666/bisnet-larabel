<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer as Pelanggan;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_pelanggan = Pelanggan::take(3)->get();

        $data_pelanggan->each(function ($pelanggan) {
            $pelanggan->payment()->create();  
        });
    }
}
