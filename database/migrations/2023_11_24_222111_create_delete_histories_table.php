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
        Schema::create('delete_histories', function (Blueprint $table) {
            $table->id();
            $table->integer("creator_id")->nullable();
            $table->bigInteger("model_id")->nullable();
            $table->string("model_name")->nullable();
            $table->tinyInteger("type")->comment("1 =  delete , 2 = restore")->nullable();
            $table->string("ip")->nullable();
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
        Schema::dropIfExists('delete_histories');
    }
};
