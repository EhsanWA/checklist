<?php

// database/migrations/2025_10_20_000000_create_inspection_lists_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inspection_lists', function (Blueprint $table) {
            $table->id();
            $table->string('title');                 // Bijv. "Meetrapport MRP2920"
            $table->text('description')->nullable(); // Optioneel toelichting
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('inspection_lists');
    }
};
