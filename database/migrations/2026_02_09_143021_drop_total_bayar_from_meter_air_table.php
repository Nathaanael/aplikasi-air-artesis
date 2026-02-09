<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('meter_air', function (Blueprint $table) {
            $table->dropColumn('total_bayar'); // hapus kolom
        });
    }

    public function down(): void
    {
        Schema::table('meter_air', function (Blueprint $table) {
            $table->integer('total_bayar')->after('tagihan_bulan_lalu');
        });
    }
};
