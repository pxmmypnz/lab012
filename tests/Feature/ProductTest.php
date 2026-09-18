<?php

namespace Tests\Feature;

use Database\Seeders\ProductSeeder;
use Database\Seeders\ProductShopSeeder;
use Database\Seeders\ShopSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Shop;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductSeeder::class);
        $this->seed(ShopSeeder::class);
        $this->seed(ProductShopSeeder::class);
    }

    public function test_products_are_listed_in_code_order(): void
    {
        $this->get('/products')
            ->assertOk()
            ->assertSeeInOrder(['PD001', 'PD002', 'PD003', 'PD004'])
            ->assertSee('Learning PHP, MySQL &amp; JavaScript', false)
            ->assertSee('560.00');
    }

    public function test_product_detail_is_loaded_by_product_code(): void
    {
        $this->get('/products/PD003')
            ->assertOk()
            ->assertSee('Product: Learning PHP, MySQL &amp; JavaScript', false)
            ->assertSee('Category')
            ->assertSee('JavaScript')
            ->assertSee('450.00')
            ->assertSee('Build interactive, data driven websites');
    }

    public function test_product_can_be_created_with_a_category(): void
    {
        $this->post('/products', [
            'code' => 'PD005',
            'name' => 'Test product',
            'category' => 'CT001',
            'price' => 232,
            'description' => 'Test description',
        ])->assertRedirect('/products/PD005');

        $this->assertDatabaseHas('products', [
            'code' => 'PD005',
            'category_id' => 1,
        ]);
    }

    public function test_unknown_product_returns_not_found(): void
    {
        $this->get('/products/unknown')->assertNotFound();
    }

    public function test_product_list_shows_shop_count(): void
    {
        $this->get('/products')
            ->assertOk()
            ->assertSeeInOrder(['PD001', '2', 'PD002', '3']);
    }

    public function test_product_list_shows_category(): void
    {
        $this->get('/products')
            ->assertOk()
            ->assertSee('PHP')
            ->assertSee('Python')
            ->assertSee('JavaScript');
    }

    public function test_product_shops_are_listed_with_product_count(): void
    {
        $this->get('/products/PD002/shops')
            ->assertOk()
            ->assertSee('Product: PD002: Shops')
            ->assertSeeInOrder(['SH001', 'SH002', 'SH003'])
            ->assertSee('No. of Products')
            ->assertSee('3');
    }

    public function test_add_shops_form_excludes_existing_shops(): void
    {
        $this->get('/products/PD001/shops/add')
            ->assertOk()
            ->assertSee('SH003')
            ->assertDontSee('SH001')
            ->assertDontSee('SH002');
    }

    public function test_shop_can_be_added_to_product(): void
    {
        $product = Product::where('code', 'PD001')->firstOrFail();
        $shop = Shop::where('code', 'SH003')->firstOrFail();

        $this->post('/products/PD001/shops', ['shop' => 'SH003'])
            ->assertRedirect();

        $this->assertDatabaseHas('product_shop', [
            'product_id' => $product->id,
            'shop_id' => $shop->id,
        ]);
    }

    public function test_shop_can_be_removed_from_product(): void
    {
        $product = Product::where('code', 'PD001')->firstOrFail();
        $shop = Shop::where('code', 'SH001')->firstOrFail();

        $this->post('/products/PD001/shops/remove', ['shop' => 'SH001'])
            ->assertRedirect();

        $this->assertDatabaseMissing('product_shop', [
            'product_id' => $product->id,
            'shop_id' => $shop->id,
        ]);
    }
}
