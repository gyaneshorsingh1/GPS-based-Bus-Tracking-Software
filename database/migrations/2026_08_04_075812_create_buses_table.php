<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();

            $table->string('bus_number')->unique();
            $table->string('vehicle_number')->unique();

            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();

            $table->unsignedInteger('capacity')->default(40);

            $table->string('route')->nullable();
            $table->string('pickup_location')->nullable();
            $table->string('drop_location')->nullable();

            $table->enum('status', [
                'active',
                'maintenance',
                'inactive'
            ])->default('active');

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};