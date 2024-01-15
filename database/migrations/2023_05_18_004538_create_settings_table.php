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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo');
            $table->string('black_logo');
            $table->string('slip_stamp');
            $table->string('admin_signature');
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->text('website_url')->nullable();
            $table->string('currency_symbol');
            $table->string('favicon');
            $table->string('banner')->nullable();
            $table->string('language')->nullable();
            $table->string('country')->nullable();
            $table->string('area')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->text('address')->nullable();
            $table->text('facebook_link')->nullable();
            $table->text('instagram_link')->nullable();
            $table->text('linked_in_link')->nullable();
            $table->text('twitter_link')->nullable();
            $table->boolean('discrepancy_leave_allow_enable_disable')->default(0);
            $table->string('max_discrepancies')->nullable();
            $table->string('max_leaves')->nullable();
            $table->integer('insurance_eligibility')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
