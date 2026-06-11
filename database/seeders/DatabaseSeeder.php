<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Stunting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Stunting::truncate(); // hapus data lama dulu

        Stunting::insert([
            ['nama' => 'Ahmad',   'desa' => 'Angsau',         'kecamatan' => 'Pelaihari',     'latitude' => '-3.79610000', 'longitude' => '114.78240000', 'status' => 'Stunting',        'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Siti',    'desa' => 'Pabahanan',      'kecamatan' => 'Pelaihari',     'latitude' => '-3.79020000', 'longitude' => '114.77650000', 'status' => 'Normal',          'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Budi',    'desa' => 'Tanjung Dewa',   'kecamatan' => 'Panyipatan',    'latitude' => '-3.86010000', 'longitude' => '114.70020000', 'status' => 'Stunting',        'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Rina',    'desa' => 'Kurau',          'kecamatan' => 'Kurau',         'latitude' => '-3.52340000', 'longitude' => '114.62100000', 'status' => 'Normal',          'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Fajar',   'desa' => 'Bentok Darat',   'kecamatan' => 'Bati-Bati',     'latitude' => '-3.59580000', 'longitude' => '114.74250000', 'status' => 'Stunting',        'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dewi',    'desa' => 'Tambang Ulang',  'kecamatan' => 'Tambang Ulang', 'latitude' => '-3.68550000', 'longitude' => '114.80330000', 'status' => 'Normal',          'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Ani',     'desa' => 'Asam-Asam',      'kecamatan' => 'Jorong',        'latitude' => '-3.90440000', 'longitude' => '115.00450000', 'status' => 'Stunting',        'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Rizky',   'desa' => 'Takisung',       'kecamatan' => 'Takisung',      'latitude' => '-3.72000000', 'longitude' => '114.68000000', 'status' => 'Stunting Sedang', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Hana',    'desa' => 'Bajuin',         'kecamatan' => 'Bajuin',        'latitude' => '-3.82000000', 'longitude' => '114.85000000', 'status' => 'Stunting Sedang', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dimas',   'desa' => 'Batakan',        'kecamatan' => 'Bati-Bati',     'latitude' => '-3.63000000', 'longitude' => '114.76000000', 'status' => 'Stunting Sedang', 'created_at' => now(), 'updated_at' => now()],
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}