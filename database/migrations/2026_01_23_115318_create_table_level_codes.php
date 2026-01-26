<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('level_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')
                ->constrained('levels')
                ->onDelete('cascade');
            $table->string('code');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('level_codes');
    }
};
