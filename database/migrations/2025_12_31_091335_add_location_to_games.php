<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->unsignedBigInteger('location')->nullable();

            $table->foreign('location')
                ->references('id')
                ->on('locations')
                ->onDelete('set null'); 
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign(['location']);
            $table->dropColumn('location');
        });
    }
};
