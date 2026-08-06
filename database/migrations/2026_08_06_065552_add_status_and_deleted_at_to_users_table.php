<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds the columns required by the User Management module:
     * - `status`     : account state (active/inactive) shown in the listing.
     * - `deleted_at` : enables Soft Deletes so deleting a user does not
     *                  violate the `drivers.created_by` RESTRICT constraint
     *                  or cascade-delete linked profile records.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('status', 20)
                ->default('active')
                ->after('school_id');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropSoftDeletes();
        });
    }
};
