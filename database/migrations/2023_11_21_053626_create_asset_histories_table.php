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
        Schema::create('asset_histories', function (Blueprint $table) {
            $table->id();
            $table->integer("asset_id")->nullable();
            $table->integer("quantity")->nullable();
            $table->integer("created_by")->nullable();
            $table->integer("last_updated_by")->nullable();
            $table->integer("type")->comment("1 => induct , 2 => deduct")->nullable();
            $table->string("remarks")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_histories');
    }
};
