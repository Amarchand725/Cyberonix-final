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
        Schema::create('job_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('created_by');
            $table->bigInteger('user_id');
            $table->bigInteger('parent_designation_id')->nullable();
            $table->bigInteger('designation_id')->nullable();
            $table->bigInteger('employment_status_id');
            $table->date('joining_date');
            $table->string('vehicle_name')->nullable();
            $table->string('vehicle_cc')->nullable();
            $table->date('end_date')->nullable();
            $table->string('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_histories');
    }
};
