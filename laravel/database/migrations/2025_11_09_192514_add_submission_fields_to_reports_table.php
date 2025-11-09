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
        Schema::table('reports', function (Blueprint $table) {
            $table->string('signature_path')->nullable()->after('inspection_list_id');
            $table->string('submitted_pdf_path')->nullable()->after('signature_path');
            $table->timestamp('submitted_at')->nullable()->after('submitted_pdf_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['signature_path', 'submitted_pdf_path', 'submitted_at']);
        });
    }
};
