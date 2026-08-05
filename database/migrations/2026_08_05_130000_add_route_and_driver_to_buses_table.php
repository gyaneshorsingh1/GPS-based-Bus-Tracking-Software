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
        Schema::table('buses', function (Blueprint $table) {
            $table->foreignId('route_id')
                ->nullable()
                ->after('school_id')
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('driver_id')
                ->nullable()
                ->after('route_id')
                ->constrained()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('route_id');
            $table->dropConstrainedForeignId('driver_id');
        });
    }
};
