<?php

// database/migrations/2025_10_20_000020_create_inspection_checks_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inspection_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_category_id')->constrained()->cascadeOnDelete();
            $table->string('label');             // Bijv. "Lees de motor uit met de Vodiatool."
            $table->string('code')->nullable();  // Bijv. "Op. Rgl. M0001/M0005/M0010"
            $table->boolean('required')->default(true);
            $table->enum('severity', ['info', 'low', 'medium', 'high'])->default('info'); // optioneel
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('inspection_checks');
    }
};
