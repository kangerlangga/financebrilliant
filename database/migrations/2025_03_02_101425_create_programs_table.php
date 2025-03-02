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
        Schema::create('programs', function (Blueprint $table) {
            $table->string('id_programs')->primary();
            $table->string('code_programs')->unique();
            $table->string('category_programs');
            $table->string('title_programs');
            $table->string('subtitle_programs');
            $table->bigInteger('price_programs');
            $table->integer('admin_programs');
            $table->longText('benefit_programs');
            $table->string('visib_programs');
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
        Schema::dropIfExists('programs');
    }
};
