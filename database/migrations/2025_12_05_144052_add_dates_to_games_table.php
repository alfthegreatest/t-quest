<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->datetime('start_date')->nullable();
            $table->datetime('finish_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'finish_date']);
        });
    }
};
