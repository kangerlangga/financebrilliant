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
        Schema::create('tabungans', function (Blueprint $table) {
            $table->string('id_tabungans')->primary();
            $table->string('category_tabungans');
            $table->string('nama_tabungans');
            $table->string('rekening_tabungans')->nullable();
            $table->string('logo_tabungans')->nullable();
            $table->string('status_tabungans');
            $table->string('created_by');
            $table->string('modified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabungans');
    }
};
