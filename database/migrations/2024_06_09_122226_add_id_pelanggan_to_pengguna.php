<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdPelangganToPengguna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->unsignedInteger('id_pengguna')->nullable()->after('id_pelanggan');
 
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropForeign('pelanggan_id_pengguna_foreign');
            $table->dropColumn('id_pengguna');
        });
    }
}
