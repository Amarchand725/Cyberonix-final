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
        Schema::create('vehicle_allowances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vehicle');
            $table->bigInteger('user_id');
            $table->bigInteger('allowance');
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            $table->boolean('status')->default(1);
            $table->string('note')->nullable();
            $table->string('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_allowances');
    }
};
