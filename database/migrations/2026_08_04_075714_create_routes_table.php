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
        Schema::create('routes', function (Blueprint $table) {
    $table->id();

    $table->foreignId('school_id')->constrained()->cascadeOnDelete();

    // $table->foreignId('bus_id')
    //     ->nullable()
    //     ->constrained()
    //     ->nullOnDelete();

    $table->foreignId('driver_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->string('name');

    $table->string('route_code')->unique();

    $table->string('start_location');

    $table->string('end_location');

    $table->decimal('estimated_distance', 8, 2)->nullable();

    $table->integer('estimated_duration')->nullable(); // minutes

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
        Schema::dropIfExists('routes');
    }
};
