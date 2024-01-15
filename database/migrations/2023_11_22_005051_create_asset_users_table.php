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
        Schema::create('asset_users', function (Blueprint $table) {
            $table->id();
            $table->integer("asset_detail_id")->nullable();
            $table->integer("employee_id")->nullable();
            $table->integer("assigned_by")->nullable();
            $table->integer("unassigned_by")->nullable();
            $table->dateTime("assigned_at")->nullable();
            $table->dateTime("unassigned_at")->nullable();
            $table->dateTime("effective_date")->nullable();
            $table->dateTime("end_date")->nullable();
            $table->integer("status")->comment("1 => assigned , 2 => re assigned , 3 => return")->nullable();
            $table->integer("is_damage")->nullable()->comment("1 only when asset is damage from user , else 2");
            $table->longText("remarks")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_users');
    }
};
