<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {

            $table->id();

            // School
            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            // Employee information
            $table->string('employee_id')->unique();

            // Personal information
            $table->string('first_name');
            $table->string('last_name');

            $table->enum('gender', [
                'Male',
                'Female',
                'Other'
            ]);

            $table->date('date_of_birth');

            $table->string('phone');
            $table->string('email')->nullable();

            $table->text('address');

            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();

            // License information
            $table->string('license_number')->unique();
            $table->string('license_type');

            $table->date('license_issue_date');
            $table->date('license_expiry_date');

            $table->unsignedInteger('experience_years')->nullable();

            // Employment
            $table->date('joining_date');

            $table->enum('status', [
                'Active',
                'Inactive',
                'Suspended'
            ])->default('Active');

            // Profile
            $table->string('profile_photo')->nullable();

            // Emergency contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();

            // Additional notes
            $table->text('remarks')->nullable();

            // User who created the driver
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};