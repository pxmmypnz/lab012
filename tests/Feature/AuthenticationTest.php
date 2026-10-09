<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ProductShopSeeder;
use Database\Seeders\ShopSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_and_login_page_is_not_cached(): void
    {
        $this->get('/products')
            ->assertRedirect(route('login'));

        $this->get(route('login'))
            ->assertOk()
            ->assertHeader('Cache-Control', 'max-age=0, must-revalidate, no-cache, no-store, private');
    }

    public function test_user_can_view_products_shops_and_categories_but_cannot_change_them(): void
    {
        $user = User::where('email', 'user@my-db.com')->firstOrFail();

        $this->post(route('authentication'), [
            'email' => $user->email,
            'password' => '1234',
        ])->assertRedirect(route('products.index'));

        $this->assertAuthenticatedAs($user);

        $this->seed(ProductSeeder::class);
        $this->seed(ShopSeeder::class);
        $this->seed(ProductShopSeeder::class);
        $this->get('/products')
            ->assertOk()
            ->assertDontSee('Create Product')
            ->assertSee('href="'.route('shops.list').'"', false)
            ->assertSee('href="'.route('categories.list').'"', false);

        $this->get('/products/create')->assertForbidden();
        $this->get('/shops')->assertOk()->assertDontSee('Create Shop');
        $this->get('/shops/SH001')->assertOk()->assertSee('Pamila Shop')->assertDontSee('Update')->assertDontSee('Delete');
        $this->get('/shops/SH001/products')->assertOk()->assertDontSee('Add Products')->assertDontSee('Remove');
        $this->get('/categories')->assertOk()->assertDontSee('Create Category');
        $this->get('/categories/CT001')->assertOk()->assertSee('PHP')->assertDontSee('Update')->assertDontSee('Delete');
        $this->get('/categories/CT001/products')->assertOk()->assertSee('Programming PHP')->assertDontSee('Add Products');
        $this->get('/shops/create')->assertForbidden();
        $this->get('/categories/create')->assertForbidden();
        $this->get('/products/PD001')
            ->assertOk()
            ->assertSee('View Shops')
            ->assertDontSee('Update')
            ->assertDontSee('Delete');
        $this->get('/products/PD001/shops')
            ->assertOk()
            ->assertSee('SH001')
            ->assertSee('href="'.route('shops.view', ['shop' => 'SH001']).'"', false)
            ->assertDontSee('Add Shops')
            ->assertDontSee('Remove');
        $this->get('/products/PD001/shops/add')->assertForbidden();

        $this->post('/products/PD001/shops', ['shop' => 'SH999'])
            ->assertForbidden();

        $this->post('/shops', [
            'code' => 'SH999',
            'name' => 'Unauthorized shop',
        ])->assertForbidden();

        $this->post('/categories', [
            'code' => 'CT999',
            'name' => 'Unauthorized category',
        ])->assertForbidden();
        $this->post('/shops/SH001', ['name' => 'Unauthorized update'])->assertForbidden();
        $this->post('/categories/CT001', ['name' => 'Unauthorized update'])->assertForbidden();

        $this->assertDatabaseMissing('shops', ['code' => 'SH999']);
        $this->assertDatabaseMissing('categories', ['code' => 'CT999']);
    }

    public function test_invalid_credentials_are_reported(): void
    {
        $this->from(route('login'))
            ->post(route('authentication'), [
                'email' => 'user@my-db.com',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('credentials');

        $this->assertGuest();
    }

    public function test_user_cannot_delete_category_and_category_with_products_cannot_be_deleted(): void
    {
        $user = User::where('email', 'user@my-db.com')->firstOrFail();
        $category = Category::create([
            'code' => 'CT999',
            'name' => 'Empty category',
            'description' => 'An empty category',
        ]);

        $this->actingAs($user)
            ->post(route('categories.delete', ['category' => $category->code]))
            ->assertForbidden();

        $this->seed(ProductSeeder::class);
        $admin = User::where('email', 'admin@my-db.com')->firstOrFail();
        $this->actingAs($admin)
            ->post(route('categories.delete', ['category' => 'CT001']))
            ->assertForbidden();
    }

    public function test_admin_can_log_out(): void
    {
        $admin = User::where('email', 'admin@my-db.com')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
