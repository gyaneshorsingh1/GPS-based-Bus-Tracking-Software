<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the trip leg to attendance so each student can be marked twice per day
     * (home to school and school to home).
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('trip')->default('home_to_school')->after('bus_id');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->unique(['student_id', 'date', 'trip'], 'attendances_student_id_date_trip_unique');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique('attendances_student_id_date_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique('attendances_student_id_date_trip_unique');
            $table->unique(['student_id', 'date']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('trip');
        });
    }
};
