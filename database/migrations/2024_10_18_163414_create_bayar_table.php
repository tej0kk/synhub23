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
        Schema::create('bayar', function (Blueprint $table) {
            $table->id();
            $table->string('nama_orang');
            $table->string('nama_pembayaran');
            $table->string('nomor_rekening');
            $table->string('logo');
            $table->enum('status', ['y', 'n']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bayar');
    }
};
