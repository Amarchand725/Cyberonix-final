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
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('name_as_per_cnic');
            $table->date('date_of_birth');
            $table->boolean('sex')->default(1)->comment('1=Male, 0=Female');
            $table->string('cnic_number');
            $table->boolean('marital_status')->default(0)->comment('0=>Single, 1=Double');
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
        Schema::dropIfExists('insurances');
    }
};
