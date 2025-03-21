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
        Schema::create('finances', function (Blueprint $table) {
            $table->string('id_finances')->primary();
            $table->string('tabungan');
            $table->bigInteger('saldo_awal');
            $table->bigInteger('out_debit');
            $table->bigInteger('in_kredit');
            $table->bigInteger('saldo_akhir');
            $table->string('noted');
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
        Schema::dropIfExists('finances');
    }
};
