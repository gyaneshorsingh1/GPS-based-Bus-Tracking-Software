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

            $table->foreignId('school_id')->constrained()->cascadeOnDelete();

            $table->foreignId('driver_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('bus_number')->unique();
            $table->string('registration_number')->unique();
            $table->unsignedSmallInteger('capacity');

            $table->string('gps_device_id')->nullable()->unique();

            $table->enum('status', [
                'Active',
                'Maintenance',
                'Inactive',
            ])->default('Active');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
