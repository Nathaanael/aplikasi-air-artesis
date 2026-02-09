<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meter_air', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->integer('bulan');
            $table->integer('tahun');

            $table->integer('stand_lama');
            $table->integer('stand_kini');
            $table->integer('pemakaian');

            $table->integer('tagihan_bulan_lalu')->nullable();

            $table->timestamps();

            $table->unique(['user_id','bulan','tahun']); // cegah dobel input
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meter_air');
    }
};
