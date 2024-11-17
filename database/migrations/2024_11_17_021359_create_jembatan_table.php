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
        Schema::create('jembatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jembatan');
            $table->decimal('panjang');
            $table->year('tahun_pembangunan');
            $table->enum('jenis', ['rangka', 'beton', 'gantung']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jembatan');
    }
};
