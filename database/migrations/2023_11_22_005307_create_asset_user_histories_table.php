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
        Schema::create('asset_user_histories', function (Blueprint $table) {
            $table->id();
            $table->integer("asset_user_id")->nullable()->comment("this is the primary key of Asset Users Table , here it is using as foreign Key");
            $table->integer("asset_detail_id")->nullable();
            $table->integer("employee_id")->nullable();
            $table->integer("creator_id")->nullable();
            $table->dateTime("date")->nullable();
            $table->longText("remarks")->nullable();
            $table->integer("type")->comment("1 = assign , 2 = unassign")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_user_histories');
    }
};
