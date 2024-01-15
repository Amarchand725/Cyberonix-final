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
        Schema::create('applied_positions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pre_employee_id');
            $table->string('applied_for_position');
            $table->string('expected_salary');
            $table->string('expected_joining_date');
            $table->string('source_of_this_post');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applied_positions');
    }
};
