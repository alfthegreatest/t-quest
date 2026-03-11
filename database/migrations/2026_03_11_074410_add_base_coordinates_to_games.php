<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->geometry('base_location', subtype: 'point', srid: 4326)
                ->default(DB::raw("ST_GeomFromText('POINT(21.039 52.236)', 4326)"));
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('base_location');
        });
    }
};
