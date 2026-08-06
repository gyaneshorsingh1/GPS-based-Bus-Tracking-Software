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
        Schema::dropIfExists('bus_route');
        Schema::dropIfExists('bus_driver');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('bus_route', function (Blueprint $table) {
            $table->foreignId('bus_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('route_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->primary(['bus_id', 'route_id']);
        });

        Schema::create('bus_driver', function (Blueprint $table) {
            $table->foreignId('bus_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('driver_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->primary(['bus_id', 'driver_id']);
        });
    }
};
