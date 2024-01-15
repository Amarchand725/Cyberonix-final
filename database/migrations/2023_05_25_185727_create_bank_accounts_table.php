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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_holder_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('bank_name');
            $table->string('branch_code');
            $table->string('iban');
            $table->string('account');
            $table->string('title');
            $table->string('education')->nullable();
            $table->string('last_employer_name')->nullable();
            $table->string('last_salary')->nullable();
            $table->string('last_designation')->nullable();
            $table->boolean('status')->default(1)->comment('1=active, 0=in-active');
            $table->string('deleted_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
