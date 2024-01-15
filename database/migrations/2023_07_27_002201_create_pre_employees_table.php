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
        Schema::create('pre_employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('manager_id');
            $table->string('name');
            $table->string('father_name');
            $table->string('email');
            $table->string('date_of_birth');
            $table->string('cnic');
            $table->string('contact_no');
            $table->string('emergency_number');
            $table->text('address');
            $table->string('apartment');
            $table->string('marital_status');
            $table->integer('status')->default(0)->comment('0=pending, 1=approved, 2=rejected');
            $table->string('note')->nullable()->comment('Note if any');
            $table->boolean('is_exist')->nullable(0)->comment('Duplicate or single entry');
            $table->string('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_employees');
    }
};
