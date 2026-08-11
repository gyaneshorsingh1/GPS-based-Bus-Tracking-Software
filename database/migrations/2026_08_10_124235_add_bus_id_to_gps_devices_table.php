<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('gps_devices', 'bus_id')) {
            return;
        }

        Schema::table('gps_devices', function (Blueprint $table) {
            $table->foreignId('bus_id')
                ->nullable()
                ->unique()
                ->constrained('buses')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('gps_devices', function (Blueprint $table) {
            $table->dropForeign(['bus_id']);
            $table->dropUnique(['gps_devices_bus_id_unique']);
            $table->dropColumn('bus_id');
        });
    }
};