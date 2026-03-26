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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_number')->unique();
            $table->integer('asset_sap_code');
            $table->string('asset_name');
            $table->string('description')->nullable();
            $table->string('category');
            $table->string('asset_owner');
            $table->string('current_owner');
            $table->string('location');
            $table->date('acquisition_date');
            $table->bigInteger('acquisition_cost')->nullable();
            $table->bigInteger('depreciation')->nullable();
            $table->string('condition');
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
