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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan');
            $table->bigInteger('user_id');
            $table->bigInteger('produk_id');
            $table->string('perusahaan')->nullable();
            $table->integer('jumlah_orang')->nullable();
            $table->date('tanggal_1');
            $table->date('tanggal_2')->nullable();
            $table->time('jam_1')->nullable();
            $table->time('jam_2')->nullable();
            $table->string('keterangan')->nullable();
            $table->enum('status',['1', '2', '3']);
            $table->bigInteger('bayar_id')->nullable();
            $table->string('bukti')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
