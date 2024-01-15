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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('ticket_category_id');
            $table->bigInteger('reason_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('attachment')->nullable();
            $table->string('note')->nullable();
            $table->timestamp('is_manager_approved')->nullable();
            $table->timestamp('is_concerned_approved')->nullable();
            $table->integer('status')->default(0)->comment('0=pending, 1=approved by RA, 3=completed');
            $table->string('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
