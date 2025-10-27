<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'inspection_list_id')) {
                $table->foreignId('inspection_list_id')
                    ->nullable()
                    ->after('status')
                    ->constrained('inspection_lists')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'inspection_list_id')) {
                // Drop the FK no matter what it was named
                try {
                    $table->dropConstrainedForeignId('inspection_list_id');
                } catch (\Throwable $e) {
                    try {
                        $table->dropForeign('reports_inspection_list_id_foreign');
                    } catch (\Throwable $e) {
                    }
                    try {
                        $table->dropForeign(['inspection_list_id']);
                    } catch (\Throwable $e) {
                    }
                }
                try {
                    $table->dropColumn('inspection_list_id');
                } catch (\Throwable $e) {
                }
            }
        });
    }
};
