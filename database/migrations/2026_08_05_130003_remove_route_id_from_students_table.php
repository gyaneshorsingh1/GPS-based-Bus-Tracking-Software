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
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'route_id')) {
                $table->dropConstrainedForeignId('route_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'route_id')) {
                $table->foreignId('route_id')
                    ->nullable()
                    ->after('bus_id')
                    ->constrained()
                    ->nullOnDelete();
            }
        });
    }
};
