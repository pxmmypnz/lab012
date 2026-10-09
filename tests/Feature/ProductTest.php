<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ProductShopSeeder;
use Database\Seeders\ShopSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::where('email', 'admin@my-db.com')->firstOrFail());
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

    public function test_status_message_is_displayed_in_the_main_layout(): void
    {
        $this->withSession(['status' => 'Product PD005 was created.'])
            ->get('/products')
            ->assertOk()
            ->assertSee('<div role="status">Product PD005 was created.</div>', false);
    }

    public function test_product_can_be_created_with_a_category(): void
    {
        $this->withSession([
            'bookmarks.products.create' => '/products?term=php',
        ])->post('/products', [
            'code' => 'PD005',
            'name' => 'Test product',
            'category' => 'CT001',
            'price' => 232,
            'description' => 'Test description',
        ])
            ->assertRedirect('/products/PD005')
            ->assertSessionHas('status', 'Product PD005 was created.')
            ->assertSessionHas('bookmarks.products.view', '/products?term=php')
            ->assertSessionMissing('bookmarks.products.create');

        $this->assertDatabaseHas('products', [
            'code' => 'PD005',
            'category_id' => 1,
        ]);
    }

    public function test_create_database_errors_are_shown_and_form_input_is_preserved(): void
    {
        $this->followingRedirects()
            ->from('/products/create')
            ->post('/products', [
                'code' => 'PD001',
                'name' => 'Duplicate product',
                'category' => 'CT001',
                'price' => 232,
                'description' => 'Test description',
            ])
            ->assertOk()
            ->assertSee('role="alert"', false)
            ->assertSee('value="Duplicate product"', false);
    }

    public function test_product_can_be_updated_with_a_flash_message_and_forwarded_back_link(): void
    {
        $this->withSession([
            'bookmarks.products.update' => '/products?term=php',
        ])->post('/products/PD001', [
            'code' => 'PD001',
            'name' => 'Updated product',
            'category' => 'CT001',
            'price' => 123,
            'description' => 'Updated description',
        ])
            ->assertRedirect('/products/PD001')
            ->assertSessionHas('status', 'Product PD001 was updated.')
            ->assertSessionHas('bookmarks.products.view', '/products?term=php')
            ->assertSessionMissing('bookmarks.products.update');

        $this->assertDatabaseHas('products', [
            'code' => 'PD001',
            'name' => 'Updated product',
        ]);
    }

    public function test_duplicate_product_code_error_preserves_update_form_input(): void
    {
        $this->followingRedirects()
            ->from('/products/PD001/update')
            ->post('/products/PD001', [
                'code' => 'PD002',
                'name' => 'Attempted product name',
                'category' => 'CT003',
                'price' => 1345,
                'description' => 'Attempted product description',
            ])
            ->assertOk()
            ->assertSee('role="alert"', false)
            ->assertSee('products.code', false)
            ->assertSee('value="PD002"', false)
            ->assertSee('value="Attempted product name"', false)
            ->assertSee('value="CT003" selected', false)
            ->assertSee('value="1345"', false)
            ->assertSee('Attempted product description');
    }

    public function test_missing_required_description_shows_database_error_and_preserves_other_input(): void
    {
        $this->followingRedirects()
            ->from('/products/PD001/update')
            ->post('/products/PD001', [
                'code' => 'PD001',
                'name' => 'Updated name',
                'category' => 'CT003',
                'price' => 345,
                'description' => '',
            ])
            ->assertOk()
            ->assertSee('role="alert"', false)
            ->assertSee('products.description', false)
            ->assertSee('value="PD001"', false)
            ->assertSee('value="Updated name"', false)
            ->assertSee('value="CT003" selected', false)
            ->assertSee('value="345"', false)
            ->assertSee('<textarea id="product-description" name="description" rows="8" required></textarea>', false);
    }

    public function test_product_can_be_deleted_with_a_flash_message_and_bookmarked_redirect(): void
    {
        $this->withSession([
            'bookmarks.products.delete' => '/products?term=php',
        ])->post('/products/PD001/delete')
            ->assertRedirect('/products?term=php')
            ->assertSessionHas('status', 'Product PD001 was deleted.');

        $this->assertDatabaseMissing('products', ['code' => 'PD001']);
    }

    public function test_shop_can_be_added_to_and_removed_from_a_product_with_status_messages(): void
    {
        $this->post('/products/PD001/shops', ['shop' => 'SH003'])
            ->assertRedirect()
            ->assertSessionHas('status', 'Shop SH003 was added to Product PD001.');

        $this->post('/products/PD001/shops/remove', ['shop' => 'SH003'])
            ->assertRedirect()
            ->assertSessionHas('status', 'Shop SH003 was removed from Product PD001.');
    }

    public function test_product_shop_pages_preserve_their_back_links(): void
    {
        $this->get('/products/PD001')
            ->assertOk()
            ->assertSessionHas('bookmarks.products.view-shops', url('/products/PD001'));

        $this->get('/products/PD001/shops?term=shop')
            ->assertOk()
            ->assertSee('href="'.url('/products/PD001').'"', false)
            ->assertSessionHas(
                'bookmarks.products.add-shops-form',
                url('/products/PD001/shops?term=shop'),
            )
            ->assertSessionHas('bookmarks.shops.view', url('/products/PD001/shops?term=shop'));

        $this->get('/products/PD001/shops/add')
            ->assertOk()
            ->assertSessionHas('bookmarks.shops.view', url('/products/PD001/shops/add'));
    }

    public function test_update_form_cancel_link_uses_its_bookmark(): void
    {
        $this->withSession([
            'bookmarks.products.update-form' => '/products?term=php',
        ])->get('/products/PD001/update')
            ->assertOk()
            ->assertSee('href="/products?term=php"', false);
    }

    public function test_product_detail_bookmarks_update_form_and_return_url(): void
    {
        $this->withSession([
            'bookmarks.products.view' => '/products?term=php',
        ])->get('/products/PD001')
            ->assertOk()
            ->assertSessionHas('bookmarks.products.delete', '/products?term=php')
            ->assertSessionHas('bookmarks.products.update-form', url('/products/PD001'))
            ->assertSessionHas('bookmarks.products.update', '/products?term=php');
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
