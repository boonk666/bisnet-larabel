<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndKodePembayaranToPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('kode_pembayaran')->unique()->after('id_pelanggan');
            $table->string('status')->default('created')->after('kode_pembayaran');
            $table->string('snap_url')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn(['kode_pembayaran', 'status', 'snap_url']);
        });
    }
}
