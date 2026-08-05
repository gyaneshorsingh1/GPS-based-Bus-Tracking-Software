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
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')
                ->constrained('parent_profiles')
                ->cascadeOnDelete();

            $table->foreignId('bus_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('route_id')->nullable()->constrained()->nullOnDelete();

            $table->string('admission_no')->unique();

            $table->string('first_name');
            $table->string('last_name')->nullable();

            $table->date('date_of_birth');

            $table->enum('gender', ['Male', 'Female', 'Other']);

            $table->string('grade');
            $table->string('section')->nullable();
            $table->string('roll_no')->nullable();

            $table->string('pickup_location');
            $table->string('drop_location');

            $table->decimal('pickup_latitude', 10, 7)->nullable();
            $table->decimal('pickup_longitude', 10, 7)->nullable();

            $table->decimal('drop_latitude', 10, 7)->nullable();
            $table->decimal('drop_longitude', 10, 7)->nullable();

            $table->string('photo')->nullable();

            $table->boolean('is_active')->default(true);

            $table->softDeletes();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
