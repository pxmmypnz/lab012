<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $yourName = 'Pamila';
        $friendName = 'Pammy';
        $currentTstmp = now();

        DB::table('shops')->insert([
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'SH001',
                'name' => "{$yourName} Shop",
                'owner' => "{$yourName}",
                'latitude' => '18.8004538',
                'longitude' => '98.9488998',
                'address' => "College of Arts, Media and Technology\r\n239 Huaykaew Rd., Suthep,\r\nMueang Chiang Mai District, Chiang Mai 50200",
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'SH002',
                'name' => "{$friendName} Shop",
                'owner' => "{$friendName}",
                'latitude' => '18.7921726',
                'longitude' => '98.9575002',
                'address' => "Chiang Mai University Cooperative Store\r\n239 Huaykaew Rd., Suthep,\r\nMueang Chiang Mai District, Chiang Mai 50200",
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'SH003',
                'name' => 'Other Shop',
                'owner' => "{$yourName}",
                'latitude' => '18.7989343',
                'longitude' => '98.9518081',
                'address' => "Innovation Learning Center, ILC\r\n239 Huaykaew Rd., Suthep,\r\nMueang Chiang Mai District, Chiang Mai 50200",
            ],
        ]);
    }
}
