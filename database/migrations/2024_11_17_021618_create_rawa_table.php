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
        Schema::create('rawa', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rawa');
            $table->decimal('luas');
            $table->decimal('kedalaman');
            $table->enum('kriteria', ['payau', 'lebak', 'tawar']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rawa');
    }
};
