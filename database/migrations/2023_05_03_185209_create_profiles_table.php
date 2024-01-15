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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('employment_id')->nullable();
            $table->bigInteger('cover_image_id')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->boolean('marital_status')->default(0)->comment('1=married, 0=single');
            $table->string('fathers_name')->nullable();
            $table->string('mothers_name')->nullable();
            $table->string('social_security_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('about_me')->nullable();
            $table->string('address')->nullable();
            $table->string('profile')->nullable();
            $table->string('cnic_front')->nullable();
            $table->string('cnic_back')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
