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
        Schema::create('resignations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('created_by');
            $table->bigInteger('employee_id');
            $table->bigInteger('is_manager_approved')->nullable();
            $table->bigInteger('is_concerned_approved')->nullable();
            $table->bigInteger('employment_status_id');
            $table->string('subject')->nullable();
            $table->date('resignation_date');
            $table->text('reason_for_resignation')->nullable();
            $table->string('notice_period');
            $table->string('last_working_date')->nullable();
            $table->string('comment')->nullable();
            $table->boolean('rehire_eligibility')->default(1)->comment('A boolean field indicating whether the employee is eligible for rehire in the future.');
            $table->boolean('is_rehired')->default(0)->comment('If a user re-hired it will set log');
            $table->string('resignation_letter')->nullable();
            $table->integer('status')->default(0)->comment('0=pending, 1=approved, 2-rejected');
            $table->string('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resignations');
    }
};
