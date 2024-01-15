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
        Schema::create('attendance_summaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('attendance_id');
            $table->bigInteger('user_id');
            $table->string('in_date');
            $table->string('out_date')->nullable();
            $table->string('behavior')->comment('punch_in');
            $table->string('behavior_out')->nullable()->comment('punch_out');
            $table->string('attendance_type')->comment('Late-in, Half-day, Early-out');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_summaries');
    }
};
