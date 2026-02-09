<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan kolom ke warga
        Schema::table('warga', function (Blueprint $table) {
            $table->string('nama')->nullable()->after('user_id');
            $table->string('alamat')->nullable()->after('nama');
            $table->string('rt')->nullable()->after('alamat');
            $table->string('rw')->nullable()->after('rt');
        });

        // 2. Copy data dari users ke warga
        DB::table('warga')->get()->each(function ($warga) {
            $user = DB::table('users')->where('id', $warga->user_id)->first();
            if ($user) {
                DB::table('warga')->where('id', $warga->id)->update([
                    'nama' => $user->nama,
                    'alamat' => $user->alamat,
                    'rt' => $user->rt,
                    'rw' => $user->rw,
                ]);
            }
        });

        // 3. Hapus kolom dari users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama', 'alamat', 'rt', 'rw']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Tambahkan kembali kolom ke users
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama')->nullable();
            $table->string('alamat')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
        });

        // 2. Copy balik data ke users
        DB::table('warga')->get()->each(function ($warga) {
            $user = DB::table('users')->where('id', $warga->user_id)->first();
            if ($user) {
                DB::table('users')->where('id', $user->id)->update([
                    'nama' => $warga->nama,
                    'alamat' => $warga->alamat,
                    'rt' => $warga->rt,
                    'rw' => $warga->rw,
                ]);
            }
        });

        // 3. Hapus kolom dari warga
        Schema::table('warga', function (Blueprint $table) {
            $table->dropColumn(['nama', 'alamat', 'rt', 'rw']);
        });
    }
};
