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
        Schema::create('insurance_metas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('insurance_id');
            $table->string('relationship')->comment('e.g: wife, son, daughter');
            $table->string('name');
            $table->boolean('sex')->default(1)->comment('1=Male, 0=Female');
            $table->string('cnic_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_metas');
    }
};
