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
            $table->dropConstrainedForeignId('driver_id');

            $table->string('make')->nullable()->after('registration_number');
            $table->string('model')->nullable()->after('make');
            $table->unsignedSmallInteger('year')->nullable()->after('model');

            $table->enum('fuel_type', [
                'Diesel',
                'Petrol',
                'Electric',
                'CNG',
                'Hybrid',
            ])->nullable()->after('capacity');

            $table->string('insurance_number')->nullable()->after('gps_device_id');
            $table->date('insurance_expiry_date')->nullable()->after('insurance_number');
            $table->date('last_service_date')->nullable()->after('insurance_expiry_date');

            $table->text('notes')->nullable()->after('status');

            $table->foreignId('created_by')
                ->nullable()
                ->after('notes')
                ->constrained('users')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');

            $table->dropColumn([
                'make',
                'model',
                'year',
                'fuel_type',
                'insurance_number',
                'insurance_expiry_date',
                'last_service_date',
                'notes',
            ]);

            $table->foreignId('driver_id')
                ->nullable()
                ->after('school_id')
                ->constrained()
                ->nullOnDelete();
        });
    }
};
