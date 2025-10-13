<?php
// database/migrations/2025_10_08_000000_alter_reports_to_ship_fields.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // hernoem 'title' -> 'schip_naam' (als 'title' bestaat)
            if (Schema::hasColumn('reports', 'title') && !Schema::hasColumn('reports', 'schip_naam')) {
                $table->renameColumn('title', 'schip_naam');
            }

            // nieuwe velden
            if (!Schema::hasColumn('reports', 'schip_nummer')) {
                $table->string('schip_nummer', 50)->nullable()->after('schip_naam');
            }
            if (!Schema::hasColumn('reports', 'schip_bouwjaar')) {
                $table->integer('schip_bouwjaar')->nullable()->after('schip_nummer');
            }
            if (!Schema::hasColumn('reports', 'monteur')) {
                $table->string('monteur', 120)->nullable()->after('schip_bouwjaar');
            }

            // zorg dat status bestaat (draft/submitted/archived)
            if (!Schema::hasColumn('reports', 'status')) {
                $table->enum('status', ['draft', 'submitted', 'archived'])->default('draft')->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // rollback: maak 'title' terug als het nog niet bestaat
            if (Schema::hasColumn('reports', 'schip_naam') && !Schema::hasColumn('reports', 'title')) {
                $table->renameColumn('schip_naam', 'title');
            }
            if (Schema::hasColumn('reports', 'schip_nummer')) {
                $table->dropColumn('schip_nummer');
            }
            if (Schema::hasColumn('reports', 'schip_bouwjaar')) {
                $table->dropColumn('schip_bouwjaar');
            }
            if (Schema::hasColumn('reports', 'monteur')) {
                $table->dropColumn('monteur');
            }
            // status laten staan (optioneel kun je 'm droppen)
            // if (Schema::hasColumn('reports', 'status')) $table->dropColumn('status');
        });
    }
};
