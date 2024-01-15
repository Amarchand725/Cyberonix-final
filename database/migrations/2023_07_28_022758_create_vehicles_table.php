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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('owner_id');
            $table->string('name');
            $table->string('thumbnail')->nullable();
            $table->string('model');
            $table->string('body_type');
            $table->string('assembly');
            $table->string('model_year');
            $table->string('color');
            $table->string('transmission');
            $table->string('engine_type');
            $table->string('engine_number');
            $table->string('chassis_number');
            $table->string('engine_capacity');
            $table->string('mileage');
            $table->string('registration_province');
            $table->string('registration_city');
            $table->string('registration_number');
            $table->string('additional')->nullable();
            $table->string('video')->nullable();
            $table->boolean('status')->default(1);
            $table->string('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
