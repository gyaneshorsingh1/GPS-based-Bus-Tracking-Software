<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'school_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('school_id')
                    ->nullable()
                    ->after('email')
                    ->constrained('schools')
                    ->nullOnDelete();
            });
        }

        DB::table('school_admins')
            ->select('user_id', 'school_id')
            ->whereNotNull('school_id')
            ->orderBy('id')
            ->each(function ($row) {
                DB::table('users')
                    ->where('id', $row->user_id)
                    ->update(['school_id' => $row->school_id]);
            });

        DB::table('parent_profiles')
            ->select('user_id', 'school_id')
            ->whereNotNull('school_id')
            ->orderBy('id')
            ->each(function ($row) {
                DB::table('users')
                    ->where('id', $row->user_id)
                    ->update(['school_id' => $row->school_id]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('school_id');
        });
    }
};
