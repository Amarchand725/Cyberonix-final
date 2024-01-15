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
        Schema::create('asset_details', function (Blueprint $table) {
            $table->id();
            $table->integer("asset_id")->nullable();
            $table->string("uid")->nullable();
            $table->integer("assignee")->nullable();
            $table->integer("is_damage")->comment("	1 = damage, 2 = not damage	")->nullable();
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
        Schema::dropIfExists('asset_details');
    }
};
