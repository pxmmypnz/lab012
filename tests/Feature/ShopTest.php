<?php

namespace Tests\Feature;

use Database\Seeders\ProductSeeder;
use Database\Seeders\ProductShopSeeder;
use Database\Seeders\ShopSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ShopSeeder::class);
        $this->seed(ProductSeeder::class);
        $this->seed(ProductShopSeeder::class);
    }

    public function test_shops_are_listed_in_code_order(): void
    {
        $this->get('/shops')
            ->assertOk()
            ->assertSee('Shop: List')
            ->assertSeeInOrder(['SH001', 'SH002', 'SH003'])
            ->assertSee('Phanu Shop')
            ->assertSee('Phanuwat Shop');
    }

    public function test_shop_detail_is_loaded_by_shop_code(): void
    {
        $this->get('/shops/SH001')
            ->assertOk()
            ->assertSee('Shop: Phanu Shop')
            ->assertSee('18.80045380')
            ->assertSee('98.94889980')
            ->assertSee('College of Arts, Media and Technology');
    }

    public function test_unknown_shop_returns_not_found(): void
    {
        $this->get('/shops/unknown')->assertNotFound();
    }

    public function test_shop_products_show_categories(): void
    {
        $this->get('/shops/SH001/products')
            ->assertOk()
            ->assertDontSee('Create Product')
            ->assertSee('Category')
            ->assertSee('No. of Shops')
            ->assertSee('PHP')
            ->assertSee('JavaScript');
    }

    public function test_shop_add_products_form_excludes_existing_products(): void
    {
        $this->get('/shops/SH003/products/add')
            ->assertOk()
            ->assertSee('PD001')
            ->assertDontSee('PD002')
            ->assertDontSee('PD003');
    }

    public function test_product_can_be_added_and_removed_from_shop(): void
    {
        $this->post('/shops/SH003/products', ['product' => 'PD001'])
            ->assertRedirect();

        $this->assertDatabaseHas('product_shop', [
            'product_id' => 1,
            'shop_id' => 3,
        ]);

        $this->post('/shops/SH003/products/remove', ['product' => 'PD001'])
            ->assertRedirect();

        $this->assertDatabaseMissing('product_shop', [
            'product_id' => 1,
            'shop_id' => 3,
        ]);
    }

    public function test_shop_products_can_be_filtered_by_category_name(): void
    {
        $this->get('/shops/SH001/products?term=JavaScript')
            ->assertOk()
            ->assertSee('PD003')
            ->assertDontSee('PD001');
    }
}
