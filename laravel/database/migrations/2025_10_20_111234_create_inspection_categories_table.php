<?php

// database/migrations/2025_10_20_000010_create_inspection_categories_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inspection_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_list_id')->constrained()->cascadeOnDelete();
            $table->string('name');              // Bijv. "1211 - Hoofdmachine installatie"
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('inspection_categories');
    }
};
