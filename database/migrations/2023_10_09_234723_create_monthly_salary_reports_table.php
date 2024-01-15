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
        Schema::create('monthly_salary_reports', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id');
            $table->string('month_year');
            $table->bigInteger('actual_salary')->default(0);
            $table->bigInteger('car_allowance')->default(0);
            $table->bigInteger('earning_salary')->default(0);
            $table->bigInteger('approved_days_amount')->default(0);
            $table->bigInteger('deduction')->default(0);
            $table->bigInteger('net_salary')->default(0);
            $table->date('generated_date');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_salary_reports');
    }
};
