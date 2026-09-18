<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Shop02Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentTstmp = now();

        DB::table('shops')->insert([
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'SH101',
                'name' => 'Adam Shop',
                'owner' => 'Adam',
                'latitude' => '19.93633',
                'longitude' => '25.54209',
                'address' => "Address 101_1\r\nAddress 101_2",
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'SH102',
                'name' => 'MadA Shop',
                'owner' => 'Adam',
                'latitude' => '22.41123',
                'longitude' => '-90.57680',
                'address' => "Address 102_1\r\nAddress 102_2",
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'SH103',
                'name' => 'Page Turners',
                'owner' => 'Bob',
                'latitude' => '-24.99135',
                'longitude' => '80.35458',
                'address' => "Address 103_1\r\nAddress 103_2",
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'SH104',
                'name' => 'Literary Lounge',
                'owner' => 'Cindy',
                'latitude' => '54.11798',
                'longitude' => '40.51694',
                'address' => "Address 104_1\r\nAddress 104_2",
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'SH105',
                'name' => 'Novel Nook',
                'owner' => 'Dian',
                'latitude' => '29.64557',
                'longitude' => '114.18390',
                'address' => "Address 105_1\r\nAddress 105_2",
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'SH106',
                'name' => 'Readery Place',
                'owner' => 'Evan',
                'latitude' => '38.74781',
                'longitude' => '-125.22089',
                'address' => "Address 106_1\r\nAddress 106_2",
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'SH107',
                'name' => 'Tome Treasures',
                'owner' => 'Frank',
                'latitude' => '3.58527',
                'longitude' => '24.96360',
                'address' => "Address 107_1\r\nAddress 107_2",
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'SH108',
                'name' => 'Paperback Palace',
                'owner' => 'Gorge',
                'latitude' => '-15.14895',
                'longitude' => '-66.80432',
                'address' => "Address 108_1\r\nAddress 108_2",
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'SH109',
                'name' => 'Ink & Paper',
                'owner' => 'Henry',
                'latitude' => '-28.30137',
                'longitude' => '120.25675',
                'address' => "Address 109_1\r\nAddress 109_2",
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'SH110',
                'name' => 'Bookish Corner',
                'owner' => 'Ivy',
                'latitude' => '18.8029089',
                'longitude' => '98.9516926',
                'address' => "Address 110_1\r\nAddress 110_2",
            ],
        ]);
    }
}
