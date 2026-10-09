<?php

namespace Tests\Feature;

use App\Models\User;
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

        $this->actingAs(User::where('email', 'admin@my-db.com')->firstOrFail());
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
            ->assertSee('Pamila Shop')
            ->assertSee('Pammy Shop');
    }

    public function test_shop_detail_is_loaded_by_shop_code(): void
    {
        $this->get('/shops/SH001')
            ->assertOk()
            ->assertSee('Shop: Pamila Shop')
            ->assertSee('18.80045380')
            ->assertSee('98.94889980')
            ->assertSee('College of Arts, Media and Technology');
    }

    public function test_shop_can_be_created_with_a_status_and_forwarded_back_link(): void
    {
        $this->withSession([
            'bookmarks.shops.create' => '/shops?term=phanu',
        ])->post('/shops', [
            'code' => 'SH004',
            'name' => 'Test Shop',
            'owner' => 'Test Owner',
            'latitude' => 18.8,
            'longitude' => 98.9,
            'address' => 'Test address',
        ])
            ->assertRedirect('/shops/SH004')
            ->assertSessionHas('status', 'Shop SH004 was created.')
            ->assertSessionHas('bookmarks.shops.view', '/shops?term=phanu')
            ->assertSessionMissing('bookmarks.shops.create');
    }

    public function test_shop_can_be_updated_with_a_status_and_forwarded_back_link(): void
    {
        $this->withSession([
            'bookmarks.shops.update' => '/shops?term=phanu',
        ])->post('/shops/SH001', [
            'code' => 'SH001',
            'name' => 'Updated Shop',
            'owner' => 'Updated Owner',
            'latitude' => 18.8,
            'longitude' => 98.9,
            'address' => 'Updated address',
        ])
            ->assertRedirect('/shops/SH001')
            ->assertSessionHas('status', 'Shop SH001 was updated.')
            ->assertSessionHas('bookmarks.shops.view', '/shops?term=phanu')
            ->assertSessionMissing('bookmarks.shops.update');
    }

    public function test_shop_can_be_deleted_with_a_status_and_bookmarked_redirect(): void
    {
        $this->withSession([
            'bookmarks.shops.delete' => '/shops?term=phanu',
        ])->post('/shops/SH003/delete')
            ->assertRedirect('/shops?term=phanu')
            ->assertSessionHas('status', 'Shop SH003 was deleted.');
    }

    public function test_shop_forms_and_detail_preserve_back_links(): void
    {
        $this->withSession([
            'bookmarks.shops.create-form' => '/shops?term=phanu',
        ])->get('/shops/create')
            ->assertOk()
            ->assertSee('href="/shops?term=phanu"', false);

        $this->withSession([
            'bookmarks.shops.view' => '/shops?term=phanu',
        ])->get('/shops/SH001')
            ->assertOk()
            ->assertSee('href="/shops?term=phanu"', false)
            ->assertSessionHas('bookmarks.shops.view-products', url('/shops/SH001'))
            ->assertSessionHas('bookmarks.shops.update-form', url('/shops/SH001'))
            ->assertSessionHas('bookmarks.shops.delete', '/shops?term=phanu');

        $this->withSession([
            'bookmarks.shops.update-form' => '/shops?term=phanu',
        ])->get('/shops/SH001/update')
            ->assertOk()
            ->assertSee('href="/shops?term=phanu"', false);
    }

    public function test_unknown_shop_returns_not_found(): void
    {
        $this->get('/shops/unknown')->assertNotFound();
    }

    public function test_shop_products_show_categories(): void
    {
        $this->withSession([
            'bookmarks.shops.view-products' => url('/shops/SH001'),
        ])->get('/shops/SH001/products')
            ->assertOk()
            ->assertDontSee('Create Product')
            ->assertSee('href="'.url('/shops/SH001').'"', false)
            ->assertSee('Category')
            ->assertSee('No. of Shops')
            ->assertSee('PHP')
            ->assertSee('JavaScript')
            ->assertSessionHas(
                'bookmarks.shops.add-products-form',
                url('/shops/SH001/products'),
            );
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
            ->assertRedirect()
            ->assertSessionHas('status', 'Product PD001 was added to Shop SH003.');

        $this->assertDatabaseHas('product_shop', [
            'product_id' => 1,
            'shop_id' => 3,
        ]);

        $this->post('/shops/SH003/products/remove', ['product' => 'PD001'])
            ->assertRedirect()
            ->assertSessionHas('status', 'Product PD001 was removed from Shop SH003.');

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
