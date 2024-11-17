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
        Schema::create('danau', function (Blueprint $table) {
            $table->id();
            $table->string('nama_danau');
            $table->string('alamat');
            $table->decimal('volume');
            $table->enum('kondisi', ['terawat', 'tidak']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danau');
    }
};
