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
        Schema::create('discrepancies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('approved_by')->nullable()->comment('Approved by');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('attendance_id');
            $table->dateTime('date');
            $table->string('type')->comment('late or early');
            $table->string('description')->nullable();
            $table->boolean('status')->default(0)->comment('0=pending, 1=approved');
            $table->boolean('is_additional')->default(0)->comment('If employee fill aditional discrepancy.');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discrepancies');
    }
};
