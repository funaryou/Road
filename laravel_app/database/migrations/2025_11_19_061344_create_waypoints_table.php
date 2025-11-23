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
        Schema::create('waypoints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tour_id');
            $table->string('name');
            $table->unsignedTinyInteger('day_number');
            $table->string('google_place_id')->nullable();
            $table->text('image_url')->nullable();
            $table->decimal('rating', 3, 1)->nullable();
            $table->decimal('lat', 9, 6); // 緯度
            $table->decimal('lng', 9, 6); // 経度
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waypoints');
    }
};
