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
        Schema::create('employee_conversion_rates', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->nullable();
            $table->integer("salary_history_id")->nullable();
            $table->string("month")->nullable();
            $table->string("year")->nullable();
            $table->string("salary")->nullable();
            $table->string("currency_code")->nullable();
            $table->string("currency_rate")->nullable();
            $table->string("conversion_amount")->nullable();
            $table->integer("status")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
