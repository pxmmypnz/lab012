<?php

namespace Tests\Feature;

use Database\Seeders\ProductSeeder;
use Database\Seeders\ProductShopSeeder;
use Database\Seeders\ShopSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductSeeder::class);
        $this->seed(ShopSeeder::class);
        $this->seed(ProductShopSeeder::class);
    }

    public function test_categories_are_listed(): void
    {
        $this->get('/categories')
            ->assertOk()
            ->assertSee('CT001')
            ->assertSee('PHP')
            ->assertSee('CT002')
            ->assertSee('Python')
            ->assertSee('CT003')
            ->assertSee('JavaScript');
    }

    public function test_category_create_form_is_available(): void
    {
        $this->get('/categories/create')
            ->assertOk()
            ->assertSee('category-code')
            ->assertSee('category-description');
    }

    public function test_category_can_be_created_with_a_status_and_forwarded_back_link(): void
    {
        $this->withSession([
            'bookmarks.categories.create' => '/categories?term=web',
        ])->post('/categories', [
            'code' => 'CT004',
            'name' => 'Web Development',
            'description' => 'Web Development Description',
        ])
            ->assertRedirect('/categories/CT004')
            ->assertSessionHas('status', 'Category CT004 was created.')
            ->assertSessionHas('bookmarks.categories.view', '/categories?term=web')
            ->assertSessionMissing('bookmarks.categories.create');
    }

    public function test_category_can_be_updated_with_a_status_and_forwarded_back_link(): void
    {
        $this->withSession([
            'bookmarks.categories.update' => '/categories?term=php',
        ])->post('/categories/CT001', [
            'code' => 'CT001',
            'name' => 'Updated PHP',
            'description' => 'Updated description',
        ])
            ->assertRedirect('/categories/CT001')
            ->assertSessionHas('status', 'Category CT001 was updated.')
            ->assertSessionHas('bookmarks.categories.view', '/categories?term=php')
            ->assertSessionMissing('bookmarks.categories.update');
    }

    public function test_category_can_be_deleted_with_a_status_and_bookmarked_redirect(): void
    {
        $this->post('/categories', [
            'code' => 'CT004',
            'name' => 'Temporary',
            'description' => 'Temporary category',
        ]);

        $this->withSession([
            'bookmarks.categories.delete' => '/categories?term=php',
        ])->post('/categories/CT004/delete')
            ->assertRedirect('/categories?term=php')
            ->assertSessionHas('status', 'Category CT004 was deleted.');
    }

    public function test_category_forms_and_detail_preserve_back_links(): void
    {
        $this->withSession([
            'bookmarks.categories.create-form' => '/categories?term=web',
        ])->get('/categories/create')
            ->assertOk()
            ->assertSee('href="/categories?term=web"', false);

        $this->withSession([
            'bookmarks.categories.view' => '/categories?term=php',
        ])->get('/categories/CT001')
            ->assertOk()
            ->assertSee('href="/categories?term=php"', false)
            ->assertSessionHas('bookmarks.categories.view-products', url('/categories/CT001'))
            ->assertSessionHas('bookmarks.categories.update-form', url('/categories/CT001'))
            ->assertSessionHas('bookmarks.categories.delete', '/categories?term=php');

        $this->withSession([
            'bookmarks.categories.update-form' => '/categories?term=php',
        ])->get('/categories/CT001/update')
            ->assertOk()
            ->assertSee('href="/categories?term=php"', false);
    }

    public function test_category_can_be_created(): void
    {
        $this->post('/categories', [
            'code' => 'CT004',
            'name' => 'Web Development',
            'description' => 'Web Development Description',
        ])->assertRedirect('/categories/CT004');
    }

    public function test_category_detail_shows_products(): void
    {
        $this->get('/categories/CT001')
            ->assertOk()
            ->assertSee('Category: PHP')
            ->assertSee('View Products')
            ->assertSee('Update')
            ->assertSee('Delete')
            ->assertDontSee('Products in PHP');
    }

    public function test_categories_can_be_filtered_by_term(): void
    {
        $this->get('/categories?term=Python')
            ->assertOk()
            ->assertSee('CT002')
            ->assertSee('Python')
            ->assertDontSee('CT001');
    }

    public function test_category_products_page_lists_products_and_shop_counts(): void
    {
        $this->withSession([
            'bookmarks.categories.view-products' => url('/categories/CT001'),
        ])->get('/categories/CT001/products')
            ->assertOk()
            ->assertSee('href="'.url('/categories/CT001').'"', false)
            ->assertSee('List of Products for PHP')
            ->assertSee('Programming PHP')
            ->assertSee('No. of Shops')
            ->assertSee('2')
            ->assertSessionHas(
                'bookmarks.categories.add-products-form',
                url('/categories/CT001/products'),
            );
    }

    public function test_category_add_products_form_excludes_products_in_category(): void
    {
        $this->get('/categories/CT001/products/add')
            ->assertOk()
            ->assertSee('PD002')
            ->assertDontSee('PD001');
    }

    public function test_product_can_be_assigned_to_category(): void
    {
        $this->post('/categories/CT001/products', ['product' => 'PD002'])
            ->assertRedirect()
            ->assertSessionHas('status', 'Product PD002 was added to Category CT001.');

        $this->assertDatabaseHas('products', [
            'code' => 'PD002',
            'category_id' => 1,
        ]);
    }

    public function test_category_add_products_form_shows_category_without_link(): void
    {
        $this->get('/categories/CT001/products/add')
            ->assertOk()
            ->assertSee('Category')
            ->assertSee('JavaScript');
    }

    public function test_unknown_category_returns_not_found(): void
    {
        $this->get('/categories/unknown')
            ->assertNotFound();
    }
}
