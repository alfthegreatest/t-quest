<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order')->default(0);
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->geometry('coordinates', subtype: 'point', srid: 4326); // 4326 = WGS84 (стандарт GPS)
            $table->unsignedInteger('availability_time');
            $table->timestamps();
            
            $table->spatialIndex('coordinates'); // for performance
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
