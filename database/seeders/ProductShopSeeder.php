<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductShopSeeder extends Seeder
{
    public function run(): void
    {
        $timestamp = now();
        $products = DB::table('products')->select('id')->get();
        $shops = DB::table('shops')->select('id')->get();

        $shopMax = min(5, count($shops));
        $relatedShops = [
            min(2, $shopMax),
            min(3, $shopMax),
            min(2, $shopMax),
            min(1, $shopMax),
            min(4, $shopMax),
        ];
        $productMax = min(count($relatedShops), count($products));
        $data = [];

        for ($productIndex = 0; $productIndex < $productMax; $productIndex++) {
            for ($shopIndex = 0; $shopIndex < $relatedShops[$productIndex]; $shopIndex++) {
                $data[] = [
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                    'product_id' => $products[$productIndex]->id,
                    'shop_id' => $shops[($productIndex + $shopIndex) % $shopMax]->id,
                ];
            }
        }

        DB::table('product_shop')->insert($data);
    }
}
