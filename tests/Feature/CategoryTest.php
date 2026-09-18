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
            ->assertSee('Category: CT001')
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
        $this->get('/categories/CT001/products')
            ->assertOk()
            ->assertSee('List of Products in PHP')
            ->assertSee('Programming PHP')
            ->assertSee('No. of Shops')
            ->assertSee('2');
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
            ->assertRedirect();

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
