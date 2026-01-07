<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;


class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            'Berlin (Germany)',
            'Krakow (Poland)',
            'Lodz (Poland)',
            'Minsk (Belarus)',
            'Moscow (Russia)',
            'Paris (France)',
            'Prague (Czechia)',
            'Rome (Italy)',
            'Vilnius (Lithuania)',
            'Warsaw (Poland)',
            'Wroclaw (Poland)',
        ];

        $timestamp = now();
        
        Location::insert(
            collect($locations)->map(fn($title) => [
                'title' => $title,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ])->toArray()
        );
    }
}