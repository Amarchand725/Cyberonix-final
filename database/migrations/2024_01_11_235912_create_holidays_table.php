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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('created_by');
            $table->string('name')->comment('Name of Occasion');
            $table->date('start_at');
            $table->date('end_at');
            $table->integer('off_days');
            $table->text('description')->nullable();
            $table->string('type')->default('universal')->comment('"Universal" => for every one has off "Customizable" => for custom employees');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
